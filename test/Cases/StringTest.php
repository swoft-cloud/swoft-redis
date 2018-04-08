<?php

namespace SwoftTest\Redis;

use Swoft\Redis\Redis;

class StringTest extends AbstractTestCase
{
    /** @var \Swoft\Redis\Redis $redis * */
    private $redis = null;

    public function setUp()
    {
        parent::setUp();
        $this->redis = new Redis();
    }

    /**
     * @test
     * @requires extension redis
     */
    public function set()
    {
        $key    = 'set-key';
        $value  = 'set-value';
        $result = $this->redis->set($key, $value);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function setCo()
    {
        $key   = 'set-key';
        $value = 'set-value';
        $key   = $this->setCoName($key);
        $value = $this->setCoName($value);

        go(function () use ($key, $value) {
            $result = $this->redis->set($key, $value);
            $this->assertTrue($result);
        });
    }

    /**
     * @test
     * @requires extension redis
     */
    public function get()
    {
        $key    = 'set-key';
        $value  = 'set-value';
        $result = $this->redis->get($key);
        $this->assertSame($result, $value);
    }
}