<?php
/**
 * Mysql工具类
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-5-17 下午4:20
 */

namespace App\Traits;

use EasySwoole\MysqliPool\Connection;
use EasySwoole\MysqliPool\Mysql;

trait MysqlTrait
{
    /**
     * Mysql初始化
     * @param null $mysqlPool
     * @return Connection|null
     */
    protected function getMysqlPool($mysqlPool = null)
    {
        if ($mysqlPool == null) {
            return Mysql::defer('default');
        }
        return Mysql::defer($mysqlPool);
    }
}
