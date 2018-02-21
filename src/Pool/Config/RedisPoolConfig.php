<?php

namespace Swoft\Redis\Pool\Config;

use Swoft\Bean\Annotation\Bean;
use Swoft\Bean\Annotation\Value;
use Swoft\Sg\BalancerSelector;
use Swoft\Pool\PoolProperties;
use Swoft\Sg\ProviderSelector;

/**
 * the pool config of redis
 *
 * @Bean()
 */
class RedisPoolConfig extends PoolProperties
{
    /**
     * the name of pool
     *
     * @Value(name="${config.cache.redis.name}", env="${REDIS_NAME}")
     * @var string
     */
    protected $name = "";

    /**
     * the maximum number of idle connections
     *
     * @Value(name="${config.cache.redis.maxIdel}", env="${REDIS_MAX_IDEL}")
     * @var int
     */
    protected $maxIdel = 6;

    /**
     * the maximum number of active connections
     *
     * @Value(name="${config.cache.redis.maxActive}", env="${REDIS_MAX_ACTIVE}")
     * @var int
     */
    protected $maxActive = 50;

    /**
     * the maximum number of wait connections
     *
     * @Value(name="${config.cache.redis.maxWait}", env="${REDIS_MAX_WAIT}")
     * @var int
     */
    protected $maxWait = 100;

    /**
     * the time of connect timeout
     *
     * @Value(name="${config.cache.redis.timeout}", env="${REDIS_TIMEOUT}")
     * @var int
     */
    protected $timeout = 200;

    /**
     * the addresses of connection
     *
     * <pre>
     * [
     *  '127.0.0.1:88',
     *  '127.0.0.1:88'
     * ]
     * </pre>
     *
     * @Value(name="${config.cache.redis.uri}", env="${REDIS_URI}")
     * @var array
     */
    protected $uri = [];

    /**
     * whether to user provider(consul/etcd/zookeeper)
     *
     * @Value(name="${config.cache.redis.useProvider}", env="${REDIS_USE_PROVIDER}")
     * @var bool
     */
    protected $useProvider = false;

    /**
     * the default balancer is random balancer
     *
     * @Value(name="${config.cache.redis.balancer}", env="${REDIS_BALANCER}")
     * @var string
     */
    protected $balancer = BalancerSelector::TYPE_RANDOM;

    /**
     * the default provider is consul provider
     *
     * @Value(name="${config.cache.redis.provider}", env="${REDIS_PROVIDER}")
     * @var string
     */
    protected $provider = ProviderSelector::TYPE_CONSUL;

    /**
     * the index of redis db
     *
     * @Value(name="${config.cache.redis.db}", env="${REDIS_DB}")
     * @var int
     */
    protected $db = 0;

    /**
     * Whether to be serialized
     *
     * @Value(name="${config.cache.redis.serialize}", env="${REDIS_SERIALIZE}")
     * @var int
     */
    protected $serialize = 0;

    /**
     * @return int
     */
    public function getSerialize(): int
    {
        return $this->serialize;
    }

    /**
     * @return int
     */
    public function getDb(): int
    {
        return $this->db;
    }
}
