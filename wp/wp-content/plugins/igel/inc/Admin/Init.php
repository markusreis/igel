<?php


namespace Igel\Admin;


class Init
{
    public static function boot()
    {
        add_action('wp_dashboard_setup', [self::class, 'dashboardCards']);

        add_action('admin_enqueue_scripts', function () {
            wp_register_script('igel_admin_main', IGEL_BASE_URI . 'assets/dist/igel-master.bundle.min.js');
            wp_localize_script('igel_admin_main', 'igelAdminData', [
                'token'   => IGEL_API_TOKEN,
                'baseUrl' => rtrim(get_home_url(), '/') . '/wp-json/igel/admin/'
            ]);
            wp_enqueue_script('igel_admin_main');
        });
    }

    public static function dashboardCards()
    {
        global $wp_meta_boxes;
        wp_add_dashboard_widget('igel_ji_sync', 'IGEL - JustImmo Sync', [self::class, 'syncCard']);
    }

    public static function syncCard()
    {
        $lastDownload = get_option(Sync::LAST_SYNC_TIMESTAMP_OPTION, null);
        $lastDownload = is_null($lastDownload) ? 'Noch kein Sync!' : (new \DateTime())->setTimestamp($lastDownload)->format('d.m.Y - H:i') . ' Uhr';
        echo '<h2>Daten mit JustImmo synchronisieren</h2>';
        echo '<hr>';
        echo '<ul>';
        echo '<li>Aktive Immobilien: ' . wp_count_posts('realty')->publish . '</li>';
        echo '<li>Aktive Neubauprojekte: ' . wp_count_posts('newbuild')->publish . '</li>';
        echo '<li></li>';
        echo '<li><strong id="sync-last-update">Letzter Sync: ' . $lastDownload . '</strong></li>';
        echo '</ul>';
        echo '<hr>';
        echo '<ul>';
        echo '<li>Fehlende Immobilien-Bilder: <span id="sync-immo-counter" style="transition: .2s ease;padding:5px;border-radius:5px;">' . count(get_option(Sync::DOWNLOAD_LIST_REALTY_NAME)) . '</span></li>';
        echo '<li>Fehlende Makler-Bilder: <span id="sync-user-counter" style="transition: .2s ease;padding:5px;border-radius:5px;">' . count(get_option(Sync::DOWNLOAD_LIST_CONTACT_PERSONS_NAME)) . '</span></li>';
        echo '<li><strong id="sync-last-update">Letzter Sync: ' . $lastDownload . '</strong></li>';
        echo '</ul>';
        echo '<hr>';
        echo '<div style="position:relative;background:#ccc;border-radius:5px;height:10px;width:100%;"><div id="sync-progress" style="background:#13923c;border-radius:5px;height:10px;width:0%;"></div></div>';
        echo '<div style="margin-top:10px;width:100%;text-align: center;transition: .2s ease" class="button button-primary" id="igel-sync-button">Sync starten</div>';
    }
}

