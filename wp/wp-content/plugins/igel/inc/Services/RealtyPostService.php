<?php

namespace Igel\Services;

use Igel\Traits\Singleton;
use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\CacheInterface;
use Justimmo\Model\Employee;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\Realty;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use PHPMailer\PHPMailer\Exception;
use Psr\Log\LoggerInterface;

class RealtyPostService
{
    use Singleton;

    const REMOTE_ID_KEY = 'ig_realty_id';

    private static $posts = [];

    /**
     * Check if a wordpress post for a given realty exists
     *
     * @param Realty $realty
     * @return bool
     * @throws \Exception
     */
    public function exists(Realty $realty): bool
    {
        return !empty($this->getPost($realty));
    }

    /**
     * Get a Realty instance for a given wordpress post. Queries for current active post if no post given
     *
     * @param \WP_Post|null $post
     * @return Realty|null
     */
    public function getRealty(?\WP_Post $post = null): ?Realty
    {
        $pk = get_post_meta(is_null($post) ? get_the_ID() : $post->ID, self::REMOTE_ID_KEY, true);
        return empty($pk) ? null : igel()->justImmo()->query()->findPk($pk);
    }

    /**
     * Retreive wordpress post for a given realty
     *
     * @param Realty $realty
     * @return \WP_Post|null
     * @throws \Exception
     */
    public function getPost(Realty $realty): ?\WP_Post
    {
        if (!isset(self::$posts[$realty->getId()])) {
            $posts = get_posts(
                array(
                    'post_type' => 'realty',
                    'numberposts' => -1,
                    'meta_key' => self::REMOTE_ID_KEY,
                    'meta_value' => $realty->getId(),
                ));

            if (count($posts) > 2) {
                throw new \Exception('Duplicate Post Exception for Realty ID ' . $realty->getId());
            }

            self::$posts[$realty->getId()] = empty($posts) ? null : array_shift($posts);
        }

        return self::$posts[$realty->getId()];
    }

    /**
     * Update wordpress post for a given realty
     *
     * @param Realty $realty
     * @throws Exception
     */
    public function update(Realty $realty)
    {
        $wpPost = $this->getPost($realty);
        $id = wp_update_post(array_merge($this->getPostArgs($realty), ['ID' => $wpPost->ID]));

        if (is_wp_error($id)) {
            throw new Exception('Error creating Post for ' . $realty->getId()); // todo: Notification
        }

        $this->populateMeta($realty);

        return $id;
    }

    /**
     * Create a new wordpress post for a given realty
     *
     * @param Realty $realty
     * @throws Exception
     */
    public function create(Realty $realty)
    {
        $id = wp_insert_post($this->getPostArgs($realty));

        if (is_wp_error($id)) {
            throw new Exception('Error creating Post for ' . $realty->getId()); // todo: Notification
        }

        update_post_meta($id, 'ig_realty_id', $realty->getId());

        $this->populateMeta($realty);

        return $id;
    }

    /**
     * Default post args that will be used on update and on create of a post for a given realty
     *
     * @param Realty $realty
     * @return array
     */
    protected function getPostArgs(Realty $realty)
    {
        return [
            'post_type' => 'realty',
            'post_status' => 'publish',
            'post_title' => $realty->getTitle(),
            'post_content' => $realty->getDescription(),
        ];
    }

    /**
     * Update post meta information for a given realty
     *
     * @param Realty $realty
     * @throws \Exception
     */
    public function populateMeta(Realty $realty)
    {
        $post = $this->getPost($realty);

        if (empty($post)) {
            throw new \Exception('Missing Post: ' . $realty->getId());
        }

        $meta = [
            'ig_meta_description' => empty($realty->getTeaser()) ? shorten_excerpt($realty->getDescription(), 120) : $realty->getTeaser(),
            self::REMOTE_ID_KEY => $realty->getId(),
        ];

        foreach ($meta as $key => $value) {
            update_post_meta($post->ID, $key, $value);
        }
    }

    public function getMediaHtml(Realty $realty)
    {
        return array_map(function ($media) use ($realty) {
            return '<picture data-img="' . $media->ID . '"><img alt="' . esc_html($realty->getTitle()) . '" src="' . wp_get_attachment_image_url($media->ID) . '" srcset="' . wp_get_attachment_image_srcset($media->ID) . '"/></picture>';
        }, $this->getMedia($realty));
    }

    public function getMedia(Realty $realty)
    {
        return get_posts(
            [
                'numberposts' => -1,
                'post_type' => 'attachment',
                'meta_key' => 'ig_for_realty_remote',
                'meta_value' => $realty->getId()
            ]
        );
    }

    //==|===============================================================================
    //  |  AUTHOR RELATED
    //--V-------------------------------------------------------------------------------

    public function getWpUser(Employee $employee)
    {
        $users = get_users(
            array(
                'meta_key' => 'remote_user_id',
                'meta_value' => $employee->getId(),
                'number' => 1,
                'count_total' => false
            )
        );

        if (empty($users)) {
            $user = get_user_by('email', $this->getUniqueUserMail($employee));
            if ($user instanceof \WP_User) {
                return $user;
            }
        }

        return empty($users) ? null : $users[0];
    }

    public function createWpUser(Employee $employee)
    {
        $id = wp_insert_user(
            [
                'user_login' => sanitize_title($employee->getFirstName() . $employee->getLastName()),
                'user_pass' => $this->generateRandomString(),
                'user_email' => $this->getUniqueUserMail($employee),
                'first_name' => $employee->getFirstName(),
                'last_name' => $employee->getLastName(),
                'display_name' => $employee->getFirstName() . ' ' . $employee->getLastName(),
            ]
        );

        if (is_wp_error($id)) {
            throw new \Exception('Error creating new user! ' . json_encode($id));
        }

        update_user_meta($id, 'remote_email', $employee->getEmail());
        update_user_meta($id, 'phone', $employee->getMobile());
        update_user_meta($id, 'remote_user_id', $employee->getId());
        update_field('show_agent', true, 'user_' . $id);
        update_field('role', $employee->getPosition(), 'user_' . $id);

        return $id;
    }

    public function updateWpUser(Employee $employee)
    {
        $user = $this->getWpUser($employee);

        $id = wp_update_user(
            [
                'ID' => $user->ID,
                'user_login' => sanitize_title($employee->getFirstName() . $employee->getLastName()),
                'user_email' => $this->getUniqueUserMail($employee),
                'first_name' => $employee->getFirstName(),
                'last_name' => $employee->getLastName(),
                'display_name' => $employee->getFirstName() . ' ' . $employee->getLastName(),
            ]
        );

        update_user_meta($id, 'remote_email', $employee->getEmail());
        update_user_meta($id, 'phone', $employee->getMobile());
        if (!empty($employee->getPosition())) {
            update_field('role', $employee->getPosition(), 'user_' . $id);
        }
        return $id;
    }

    /**
     * @param Employee $employee
     * @return string|null
     */
    public function getUniqueUserMail(Employee $employee): string
    {
        $e = strtolower($employee->getEmail());
        // As klagenfurt@igel.at is used a few times, but emails must be random, we might have to create a non existing
        // email for the user
        return (strpos($e, 'villach') !== false || strpos($e, 'klagenfurt') !== false)
            ? sanitize_title($employee->getFirstName() . $employee->getLastName()) . '@igel-immobilien.at'
            : $employee->getEmail();
    }

    public function generateRandomString($length = 32)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"§$%&/()=?!"§$%&/()=?!"§$%&/()=?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}