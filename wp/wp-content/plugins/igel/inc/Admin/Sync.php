<?php

namespace Igel\Admin;


use Igel\Cache\DatabaseCache;
use Igel\Services\RealtyPostService;
use Igel\Traits\Singleton;
use Justimmo\Exception\AttachmentSizeNotFoundException;
use Justimmo\Model\Attachment;
use Justimmo\Model\Employee;
use Justimmo\Model\Realty;
use Justimmo\Pager\ListPager;
use PHPMailer\PHPMailer\Exception;

class Sync
{
    use Singleton;

    const DOWNLOAD_LIST_REALTY_NAME = 'igel_downloads_realty';
    const DOWNLOAD_LIST_CONTACT_PERSONS_NAME = 'igel_downloads_contacts';
    const LAST_SYNC_TIMESTAMP_OPTION = 'igel_last_sync';

    /**
     * @var RealtyPostService $posts
     */
    protected $posts;

    private function __construct()
    {
        $this->posts = RealtyPostService::getInstance();
    }

    public static function apiPrepareSync(\WP_REST_Request $request)
    {
        if (!$request->has_param('api_token') || $request->get_param('api_token') !== IGEL_API_TOKEN) {
            return new \WP_Error('invalid_token', 'Forbidden', array('status' => 403));
        }
        try {
            $res = (new self())->run();
        } catch (\Exception$e) {
            return new \WP_Error('server_error', $e->getMessage(), array('status' => 500));
        }
        return $res;
    }

    public static function downloadFileFromList(\WP_REST_Request $request)
    {
        if (!$request->has_param('api_token') || $request->get_param('api_token') !== IGEL_API_TOKEN) {
            return new \WP_Error('invalid_token', 'Forbidden', array('status' => 403));
        }
        return (new self())->downloadSingleFileFromList();
    }

    public function downloadSingleFileFromList()
    {
        $realtyList = get_option(self::DOWNLOAD_LIST_REALTY_NAME);
        $userList = get_option(self::DOWNLOAD_LIST_CONTACT_PERSONS_NAME);

        try {
            if (!empty($realtyList)) {
                (new self())->downloadAttachment(array_shift($realtyList));
                update_option(self::DOWNLOAD_LIST_REALTY_NAME, $realtyList);
            } else if (!empty($userList)) {
                (new self())->downloadAttachment(array_shift($userList));
                update_option(self::DOWNLOAD_LIST_CONTACT_PERSONS_NAME, $userList);
            }

            update_option(self::LAST_SYNC_TIMESTAMP_OPTION, time());
        } catch (\Exception $e) {
            return new \WP_Error('server_error', $e->getMessage(), array('status' => 500));
        }

        return [
            'realtyToDownload' => $realtyList,
            'userToDownload' => $userList,
        ];
    }

    public function run($complete = false)
    {
        DatabaseCache::clear();
        $allRealties = igel()->justImmo()->all();
        $allEmployees = igel()->justImmo()->employeeQuery()->setLimit(999)->find();
        $contactsDone = [];

        $allPosts = get_posts(['post_type' => 'realty', 'numberposts' => -1]);
        $existingPosts = [];

        foreach ($allEmployees as $k => $employee) {
            /** @var Employee $employee */
            if (is_null($this->posts->getWpUser($employee))) {
                $this->posts->createWpUser($employee);
            } else {
                $this->posts->updateWpUser($employee);
            }
        }

        foreach ($allRealties as $realty) {
            /** @var Realty $realty */
            if (!$this->posts->exists($realty)) {
                $existingPosts[] = $this->posts->create($realty);
            } else {
                $existingPosts[] = $this->posts->update($realty);
            }
        }

        $downloadList = $this->generateDownloadList($allRealties, $allEmployees);

        if ($complete) {
            foreach ($downloadList['realtyToDownload'] as $k => $r) {
                $this->downloadAttachment($r);
                unset($downloadList['realtyToDownload'][$k]);
            }
            foreach ($downloadList['userToDownload'] as $k => $c) {
                $this->downloadAttachment($c);
                unset($downloadList['userToDownload'][$k]);
            }

            update_option(self::DOWNLOAD_LIST_REALTY_NAME, $downloadList['realtyToDownload']);
            update_option(self::DOWNLOAD_LIST_CONTACT_PERSONS_NAME, $downloadList['userToDownload']);
        } else if (!empty($downloadList['realtyToDownload']) || !empty($downloadList['userToDownload'])) {

            $key = empty($downloadList['realtyToDownload'])
                ? 'realtyToDownload'
                : 'userToDownload';

            $toDownload = array_shift($downloadList[$key]);

            $this->downloadAttachment($toDownload);

        }

        update_option(self::LAST_SYNC_TIMESTAMP_OPTION, time());

        foreach ($allPosts as $post) {
            if (!in_array($post->ID, $existingPosts)) {
                wp_delete_post($post->ID);
            }
        }

        return $downloadList;
    }

    public function refillCache()
    {
        igel()->justImmo()->all();
        igel()->justImmo()->data()->get('regions');
    }

    public function generateDownloadList(ListPager $allRealties, ListPager $allUsers)
    {
        $realtyToDownload = [];
        $userToDownload = [];

        foreach ($allRealties as $realty) {
            /** @var Realty $realty */
            $realtyToDownload = array_merge($realtyToDownload, $this->generateSpecificDownloadList($realty, 'ig_for_realty_remote', 'ig_for_realty_post', $realty->getTitle(), 'fullhd'));
        }

        foreach ($allUsers as $employee) {
            /** @var Employee $employee */
            $userToDownload = array_merge($userToDownload, $this->generateSpecificDownloadList($employee, 'ig_remote_user_id', 'ig_for_wp_user', $employee->getFirstName() . ' ' . $employee->getLastName() . ' Portrait', 'big'));
        }

        update_option(self::DOWNLOAD_LIST_REALTY_NAME, $realtyToDownload);
        update_option(self::DOWNLOAD_LIST_CONTACT_PERSONS_NAME, $userToDownload);

        return [
            'realtyToDownload' => $realtyToDownload,
            'userToDownload' => $userToDownload,
        ];
    }

    public function generateSpecificDownloadList($model, $remoteKeyMetaName, $localKeyMetaName, $mediaTitle, $attachmentSize)
    {
        $existing = get_posts(
            [
                'post_type' => 'attachment',
                'meta_key' => $remoteKeyMetaName,
                'meta_value' => get_class($model) === Employee::class ? $this->posts->getUniqueUserMail($model) : $model->getId(),
                'numberposts' => -1
            ]
        );

        $existing = array_map(function (\WP_Post $post) {
            $post->remote_url = get_post_meta($post->ID, 'ig_remote_url', true);
            $post->remote_url_hash = get_post_meta($post->ID, 'ig_remote_url_hash', true);
            return $post;
        }, $existing);

        $missing = $model->getAttachments();
        $superfluous = $existing;

        foreach ($superfluous as $superfluousKey => $post) {
            foreach ($missing as $missingKey => $attachment) {
                /** @var Attachment $attachment */
                $tmpAttachmentSize = $this->getAvailableAttachmentSize($attachment, $attachmentSize);
                if ($post->remote_url_hash === $this->hashUrl($attachment->getUrl($tmpAttachmentSize))) {
                    unset($superfluous[$superfluousKey]);
                    unset($missing[$missingKey]);
                }
            }
        }

        $missing = array_map(function ($attachment) use ($model, $localKeyMetaName, $remoteKeyMetaName, $mediaTitle, $attachmentSize) {
            /** @var Attachment $attachment */
            $attachmentSize = $this->getAvailableAttachmentSize($attachment, $attachmentSize);

            $out = [
                'title' => $mediaTitle,
                'remoteKeyMetaName' => $remoteKeyMetaName,
                'localKeyMetaName' => $localKeyMetaName,
                'remoteKeyMetaValue' => get_class($model) === Employee::class ? $this->posts->getUniqueUserMail($model) : $model->getId(),
                'group' => $attachment->getGroup(),
                'ig_remote_url_hash' => $this->hashUrl($attachment->getUrl($attachmentSize)),
                'ig_remote_url' => $attachment->getUrl($attachmentSize),
                'localKeyMetaValue' => $model instanceof Realty ? $this->posts->getPost($model)->ID : ($this->posts->getWpUser($model)->ID ?? null)
            ];

            return $out;

        }, $missing);

        foreach ($superfluous as $s) {
            wp_delete_post($s->ID);
        }

        return $missing;
    }

    public function getAvailableAttachmentSize(Attachment $attachment, $size, $options = ['fullhd', 'big2', 'big', 'medium', 'small', 'orig'])
    {
        try {
            $attachment->getUrl($size);
            return $size;
        } catch (AttachmentSizeNotFoundException $e) {
            $size = array_shift($options);
            return $this->getAvailableAttachmentSize($attachment, $size, $options);
        }
    }

    public function hashUrl($url)
    {
        $parts = explode('/', $url);   // ....../fullhd/KASJDKASDJ.jpg
        $last = array_pop($parts);    // => KASLDKASDK.jpg
        $last = explode('.', $last);  // remove mime type
        $last = array_shift($last);   // => KSLADKASD
        $fileSize = array_pop($parts);    // => fullhd
        return sha1($fileSize . $last);
    }

    /**
     * Make sure all attachments are available as local media
     */
    public function downloadAttachment(array $details)
    {
        $attachmentId = $this->download($details['ig_remote_url'], $details['title']);
        if (!empty($attachmentId)) {
            update_post_meta($attachmentId, $details['remoteKeyMetaName'], $details['remoteKeyMetaValue']);
            update_post_meta($attachmentId, $details['localKeyMetaName'], $details['localKeyMetaValue']);
            update_post_meta($attachmentId, 'ig_remote_url', $details['ig_remote_url']);
            update_post_meta($attachmentId, 'ig_remote_url_hash', $details['ig_remote_url_hash']);

            if ($details['group'] === 'TITELBILD') {
                set_post_thumbnail(get_post($details['localKeyMetaValue']), $attachmentId);
            }

            if ($details['remoteKeyMetaName'] === 'ig_remote_user_id') {
                $portrait = get_field('portrait', 'user_' . $details['localKeyMetaValue']);
                $landscape = get_field('landscape', 'user_' . $details['localKeyMetaValue']);
                if (empty($portrait)) {
                    update_field('portrait', $attachmentId, 'user_' . $details['localKeyMetaValue']);
                }
                if (empty($landscape)) {
                    update_field('landscape', $attachmentId, 'user_' . $details['localKeyMetaValue']);
                }
            }
        } else {
            throw new \Exception('Error downloading attachment ' . $details['ig_remote_url']);
        }
    }

    protected function download($url, string $ttitle)
    {
        $file = wp_remote_get($url);

        $type = wp_remote_retrieve_header($file, 'content-type');
        $mirror = wp_upload_bits(basename($url), '', wp_remote_retrieve_body($file));

        if (isset($mirror['file'])) {

            $attachment = [
                'post_title' => $ttitle,
                'post_mime_type' => $type,
            ];

            // Add the image to your media library (won't be attached to a post)
            $attachment_id = wp_insert_attachment($attachment, $mirror['file']);
            if (!is_wp_error($attachment_id)) {

                if (wp_attachment_is_image($attachment_id)) {

                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id,
                        $mirror['file']);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                }

                return $attachment_id;
            }

            return $attachment_id;
        }

        return null;
    }
}


