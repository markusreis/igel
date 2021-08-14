<?php

namespace Igel\Services;

use Igel\Traits\Singleton;
use Justimmo\Api\JustimmoApi;
use Justimmo\Api\JustimmoApiInterface;
use Justimmo\Cache\CacheInterface;
use Justimmo\Model\Mapper\MapperInterface;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\BasicDataWrapperInterface;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Psr\Log\LoggerInterface;

class DataService extends BasicDataQuery
{
    /** @var CacheInterface $cache */
    private $cache;


    /**
     * @param JustimmoApiInterface $api
     * @param \Justimmo\Model\Wrapper\BasicDataWrapperInterface $wrapper
     * @param \Justimmo\Model\Mapper\MapperInterface MapperInterface
     */
    public function __construct(JustimmoApiInterface $api, BasicDataWrapperInterface $wrapper, MapperInterface $mapper, CacheInterface $cache)
    {
        parent::__construct($api, $wrapper, $mapper);
        $this->cache = $cache;
    }

    public function getAll()
    {
        var_dump($this->all(true));
        die();
    }

    public function get(string $key)
    {
        $cacheKey = $this->cache->generateCacheKey("data_$key");

        if (!($this->cache->get($cacheKey))) {

            switch ($key) {

                case 'zipCodes':
                    $res = $this->all(false)->findZipCodes();
                    break;

                case 'regions':
                    $res = $this->all(false)->findRegions();
                    break;

                case 'types':
                    $res = $this->all(false)->findRealtyTypes();
                    break;

                default:
                    throw new \Exception("Undefined data key $key");
            }

            $this->cache->set($cacheKey, $res);
            return $res;
        }

        return $this->cache->get($cacheKey);
    }
}