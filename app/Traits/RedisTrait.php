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

use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

trait RedisTrait
{
    /**
     * 初始化
     * @return mixed|null
     * @throws PoolEmpty
     * @throws PoolException
     */
    protected function getRedis(): RedisObject
    {
        return RedisPool::defer();
    }
}
