<?php

namespace App\Model;

use App\Traits\MysqlTrait;
use App\Utility\Pool\MysqlObject;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\MysqliPool\Connection;
use Throwable;

/**
 * Class BaseModel
 * @package App\Model
 */
class BaseModel
{
    use MysqlTrait;

    /**
     * @var MysqlObject
     */
    private $mysql;
    /**
     * @var bool
     */
    private $tractionDb;

    /**
     * BaseModel constructor.
     * @param null $mysqlObject
     */
    public function __construct($mysqlObject = null)
    {
        if ($mysqlObject) {
            $this->mysql = $mysqlObject;
            $this->tractionDb = true;
        } else {
            $this->mysql = $this->getMysqlPool();
        }
    }

    /**
     * 获取mysql对象池
     * @param null $mysqlPool
     * @return MysqlObject|Connection|null
     */
    protected function mysql($mysqlPool = null)
    {
        // 事务对象
        if ($this->tractionDb) {
            return $this->mysql;
        }

        // 自定义连接池
        if ($mysqlPool) {
            $this->mysql = $this->getMysqlPool($mysqlPool);
        }
        return $this->mysql;
    }

    /**
     * @throws Throwable
     */
    public function __destruct()
    {
        if (Config::getInstance()->getConf('DEBUG')) {
            Logger::getInstance()->console($this->mysql->getLastQuery(), 3, 'INFO');
        }
    }
}