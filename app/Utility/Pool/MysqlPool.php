<?php

namespace App\Utility\Pool;


use EasySwoole\ORM\Db\Config as ORMConfig;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Pool\Exception\Exception;

class MysqlPool
{
    /**
     * @throws Exception
     */
    public static function createObject()
    {
        $config = new ORMConfig();
        $config->setDatabase('chat');
        $config->setUser('root');
        $config->setPassword('');
        $config->setHost('127.0.0.1');
        $config->setGetObjectTimeout(3.0); //设置获取连接池对象超时时间
        $config->setIntervalCheckTime(30*1000); //设置检测连接存活执行回收和创建的周期
        $config->setMaxIdleTime(15); //连接池对象最大闲置时间(秒)
        $config->setMaxObjectNum(20); //设置最大连接池存在连接对象数量
        $config->setMinObjectNum(5); //设置最小连接池存在连接对象数量
        DbManager::getInstance()->addConnection(new Connection($config));
    }
}