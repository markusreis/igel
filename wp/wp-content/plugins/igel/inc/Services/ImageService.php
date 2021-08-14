<?php

namespace Igel\Services;

use Igel\Traits\Singleton;
use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\CacheInterface;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Psr\Log\LoggerInterface;

class ImageService
{
    use Singleton;

    private function __construct()
    {
    }

    public function get($url)
    {
    }

    public function set($url, $value)
    {
        // TODO: Implement set() method.
    }

    public function remove($key)
    {
        var_dump($key, 'remove');
        // TODO: Implement remove() method.
    }

    public function generateCacheKey($url)
    {
        // TODO: Implement generateCacheKey() method.
        return md5($url);
    }
}