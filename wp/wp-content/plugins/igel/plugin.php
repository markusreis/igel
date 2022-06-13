<?php

/**
 * Plugin Name:       IGEL Immobilien Plugin
 * Plugin URI:        https://igel-immobilien.at
 * Description:       Adds basic functionality required for IGEL Immobilien Wordpress
 * Version:           0.0.1
 * Author:            Markus Reis
 * Author URI:        https://markusreis.com/
 * Text Domain:       igel-plugin
 */

// If this file is called directly or plugin is already defined, abort.
use Igel\Admin\Init;
use Igel\Admin\Sync;
use Igel\Services\ImageService;
use Igel\Services\JustImmoService;
use Igel\Services\RealtyPostService;
use Igel\Traits\Singleton;

if (!defined('WPINC')) {
    die;
}

const IGEL_API_TOKEN = 'as80du!$iasduiAS/882812223';
define('IGEL_BASE_URI', plugin_dir_url(__FILE__));
define('IGEL_BASE_DIR', __DIR__);


require('vendor/autoload.php');

if (!class_exists('IgelPlugin')) {

    class IgelPlugin
    {
        use Singleton;

        /** @var null|JustImmoService $justImmo */
        private $justImmo;

        public function __construct()
        {
            add_action('init', function () {
                foreach (glob(__DIR__ . '/post-types/*.php') as $file) {
                    include_once($file);
                }
            });

            add_action('admin_init', array(Init::class, 'boot'));

        }

        /**
         * Get the JustImmo Service Instance
         * @return JustImmoService
         */
        public function justImmo(): JustImmoService
        {
            return JustImmoService::getInstance();
        }

        public function realtyPosts(): RealtyPostService
        {
            return RealtyPostService::getInstance();
        }

        public function images(): ImageService
        {
            return ImageService::getInstance();
        }
    }

    function igel()
    {
        return IgelPlugin::getInstance();
    }
}

/**
 * Format any float value into a printable string
 * @param $in
 * @return string
 */
function ig_price($in)
{
    if ($in <= 0) {
        return '';
    }
    return number_format($in, 2, ',', '.') . ' &euro;';
}

igel();

require_once "helper/helper.php";


add_action('rest_api_init', function () {
    register_rest_route('igel/admin/sync', 'prepare', array(
        'methods' => 'GET',
        'callback' => [Sync::class, 'apiPrepareSync'],
    ));
    register_rest_route('igel/admin/sync', 'download-media', array(
        'methods' => 'GET',
        'callback' => [Sync::class, 'downloadFileFromList'],
    ));
});