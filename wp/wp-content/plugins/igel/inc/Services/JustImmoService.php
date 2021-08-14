<?php

namespace Igel\Services;

use Igel\Cache\DatabaseCache;
use Igel\Logger\WpLogger;
use Igel\Traits\Singleton;
use Justimmo\Api\JustimmoApi;
use Justimmo\Cache\CacheInterface;
use Justimmo\Model\EmployeeQuery;
use Justimmo\Model\Mapper\V1\BasicDataMapper;
use Justimmo\Model\Mapper\V1\EmployeeMapper;
use Justimmo\Model\Query\AbstractQuery;
use Justimmo\Model\Query\BasicDataQuery;
use Justimmo\Model\RealtyQuery;
use Justimmo\Model\Wrapper\V1\BasicDataWrapper;
use Justimmo\Model\Wrapper\V1\EmployeeWrapper;
use Justimmo\Model\Wrapper\V1\RealtyWrapper;
use Justimmo\Model\Mapper\V1\RealtyMapper;
use Justimmo\Pager\ListPager;
use Psr\Log\LoggerInterface;

class JustImmoService
{
    use Singleton;

    /**
     * @var JustimmoApi $api
     */
    private $api;

    /**
     * @var RealtyMapper $realtyMapper
     */
    private $realtyMapper;

    /**
     * @var RealtyWrapper $realtyWrapper
     */
    private $realtyWrapper;

    /** @var DataService $dataService */
    private $dataService;

    private function __construct()
    {
        $this->api           = new JustimmoApi('api-12962', 'PY-wZtsZ4M', new WpLogger(), new DatabaseCache());
        $this->realtyMapper  = new RealtyMapper();
        $this->realtyWrapper = new RealtyWrapper($this->realtyMapper);
    }

    public function data()
    {
        if (empty($this->dataService)) {
            $this->dataService = new DataService($this->api, new BasicDataWrapper(), new BasicDataMapper(), new DatabaseCache());
        }
        return $this->dataService;
    }

    /**
     * Create a new Query to JustImmo
     * @return RealtyQuery
     */
    public function query(): RealtyQuery
    {
        return new RealtyQuery($this->api, $this->realtyWrapper, $this->realtyMapper);
    }

    /**
     * Get all ImmoOffers
     * @return ListPager
     */
    public function all(): ListPager
    {
        return $this->query()->setLimit(999999)->find();
    }
}



