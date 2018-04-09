<?php

namespace Swoft\Redis;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnection;
use Swoft\Redis\Profile\RedisCommandProvider;

/**
 * Sync redis connection
 */
class SyncRedisConnection extends AbstractConnection
{
    /**
     * @var \Redis
     */
    protected $connection;

    /**
     * @return void
     */
    public function createConnection()
    {
        $timeout = $this->pool->getTimeout();
        $address = $this->pool->getConnectionAddress();
        list($host, $port) = explode(":", $address);

        /* @var RedisPoolConfig $poolConfig */
        $poolConfig = $this->pool->getPoolConfig();
        $serialize  = $poolConfig->getSerialize();
        $serialize  = ((int)$serialize == 0) ? false : true;

        // init
        $redis = new \Redis();
        $redis->connect($host, $port, $timeout);
        if ($serialize) {
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        }
        $this->connection = $redis;
    }

    /**
     * @return $this
     */
    public function reconnect()
    {
        $this->createConnection();

        return $this;
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        try {
            $this->connection->ping();
            $connected = true;
        } catch (\Throwable $throwable) {
            $connected = false;
        }

        return $connected;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        /* @var RedisCommandProvider $commandProvider */
        $commandProvider = App::getBean(RedisCommandProvider::class);
        $command         = $commandProvider->createCommand($method, $arguments);
        $arguments       = $command->getArguments();
        $method          = $command->getId();

        return PhpHelper::call([$this->connection, $method], $arguments);
    }
}
