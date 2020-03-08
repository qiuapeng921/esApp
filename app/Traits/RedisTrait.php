<?php
/**
 * Redis工具类
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-5-17 下午4:20
 */

namespace App\Traits;


use EasySwoole\RedisPool\Redis;

trait RedisTrait
{
    /**
     * @param string $name
     * @return \EasySwoole\Redis\Redis|null
     */
    protected function getRedis($name = 'redis')
    {
        return Redis::defer($name);
    }
}
