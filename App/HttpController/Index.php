<?php

namespace App\HttpController;

use App\Utility\JWT;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;

class Index extends Common
{
    /**
     * @return string|void|null
     */
    public function index()
    {
        $user = $this->getRedis()->sMembers('fd');
        dd($user);
        return $this->view('index', ['demo' => '欢迎使用 EsApp']);
    }

    /**
     * http 向socket推送消息
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function push()
    {
        $message = $this->request()->getQueryParam('message') ?? 'test';
        $info = $this->getRedis()->sMembers('fd');
        if (is_array($info)) {
            foreach ($info as $item) {
                ServerManager::getInstance()->getSwooleServer()->push($item, $message);
            }
        }
    }

    /**
     * @return string|null
     */
    public function login()
    {
        return $this->view('login');
    }

    /**
     * @return string|null
     */
    public function register()
    {
        return $this->view('register');
    }

    /**
     * 默认的 websocket 测试页
     */
    public function socket()
    {
        $token = $this->request()->getQueryParam('token') ?? '';
        $data = (new JWT())->jwtDecode($token);

        if (!$token || !$data['status']) {
            return $this->login();
        }
        return $this->view('socket', ['token' => $token]);
    }
}