<?php

namespace App\Utility\Pool;


use EasySwoole\Pool\Exception\Exception;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\Redis;
use EasySwoole\RedisPool\RedisPoolException;

class RedisPool
{
    /**
     * @param RedisConfig $redisConfig
     * @param string $name
     * @throws Exception
     * @throws RedisPoolException
     */
    public static function createObject(RedisConfig $redisConfig, $name = 'redis')
    {
        $redisPoolConfig = Redis::getInstance()->register($name, new $redisConfig);
        //配置连接池连接数
        $redisPoolConfig->setMinObjectNum(5);
        $redisPoolConfig->setMaxObjectNum(20);
    }

}