<?php

namespace App\HttpController;

use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;

class Index extends Common
{
    /**
     * @return string|void|null
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function index()
    {
        $message = $this->request()->getQueryParam('message') ?? 'test';
        $info = $this->getRedis()->sMembers('fd');
        if (is_array($info)) {
            foreach ($info as $item) {
                ServerManager::getInstance()->getSwooleServer()->push($item, $message);
            }
        }
        return $this->view('index', ['demo' => '欢迎使用 EsApp']);
    }

    /**
     * 默认的 websocket 测试页
     */
    public function socket()
    {
        return $this->view('socket');
    }
}