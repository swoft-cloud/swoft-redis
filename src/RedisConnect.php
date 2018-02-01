<?php

namespace Swoft\Redis;

use Swoft\App;
use Swoft\Helper\PhpHelper;
use Swoft\Pool\AbstractConnect;
use Swoft\Redis\Exception\RedisException;
use Swoole\Coroutine\Redis;

/**
 * The connect of redis
 */
class RedisConnect extends AbstractConnect
{
    /**
     * @var Redis
     */
    protected $connect;

    /**
     * 创建连接
     */
    public function createConnect()
    {
        // 连接信息
        $timeout = $this->connectPool->getTimeout();
        $address = $this->connectPool->getConnectAddress();
        $config = $this->parseUri($address);

        /* @var \Swoft\Redis\Pool\Config\RedisPoolConfig $poolConfig*/
        $poolConfig = $this->connectPool->getPoolConfig();
        $serialize = $poolConfig->getSerialize();
        $serialize = ((int)$serialize == 0) ? false : true;

        // 创建连接
        $redis = new Redis();
        $result = $redis->connect($config['host'], $config['port'], $serialize);
        if ($result == false) {
            App::error("redis 连接失败，host=" . $config['host'] . " port=" . $config['port'] . " timeout=" . $timeout);
            return;
        }
        if (isset($config['auth']) && false === $redis->auth($config['auth'])) {
            App::error("redis 连接认证失败，host=" . $config['host'] . " port=" . $config['port'] . " timeout=" . $timeout);
            return;
        }
        if (isset($config['database']) && $config['database'] < 16 && false === $redis->select($config['database'])) {
            App::warning("redis 连接选择仓库失败，host=" . $config['host'] . " port=" . $config['port'] . " timeout=" . $timeout);
        }

        $this->connect = $redis;
    }

    /**
     * 重连
     */
    public function reConnect()
    {
    }

    /**
     * 设置延迟收包
     *
     * @param bool $defer
     */
    public function setDefer($defer = true)
    {
        $this->connect->setDefer($defer);
    }

    /**
     * 解析 uri 连接串
     *
     * @param string $uri 参考 : `tcp://127.0.0.1:6379/1?auth=password`
     *
     * @return array
     * @throws RedisException
     */
    protected function parseUri(string $uri)
    {
        $parseAry = parse_url($uri);
        if (!isset($parseAry['host']) || !isset($parseAry['port'])) {
            throw new RedisException("redis 连接 uri 格式不正确，uri= ,请参考:tcp://127.0.0.1:6379/1?auth=password" . $uri);
        }
        isset($parseAry['path']) && $parseAry['database'] = str_replace('/', '', $parseAry['path']);
        $query = $parseAry['query']?? "";
        parse_str($query, $options);
        $configs = array_merge($parseAry, $options);
        unset($configs['path']);
        unset($configs['query']);
        return $configs;
    }

    /**
     * 魔术方法，实现调用转移
     *
     * @param string $method    方面名称
     * @param array  $arguments 参数
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return PhpHelper::call([$this->connect, $method], $arguments);
    }
}
