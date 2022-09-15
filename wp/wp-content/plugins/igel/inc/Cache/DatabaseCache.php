<?php

namespace Igel\Cache;

use Justimmo\Cache\CacheInterface;

class DatabaseCache implements CacheInterface
{
    const CACHE_PREFIX = 'igel_ji_';

    static $cleared = false;

    public static function clear()
    {
        self::$cleared = true;
        global $wpdb;
        $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'options WHERE option_name LIKE "' . self::CACHE_PREFIX . '%"');
    }

    public function get($key)
    {
        return self::$cleared ? false : get_option($key);
    }

    public function set($key, $value)
    {
        update_option($key, $value);
    }

    public function remove($key)
    {
        delete_option($key);
    }

    public function generateCacheKey($url)
    {
        return self::CACHE_PREFIX . sha1($url);
    }
}