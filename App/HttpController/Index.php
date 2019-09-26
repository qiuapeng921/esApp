<?php

namespace App\HttpController;

class Index extends Common
{
    public function index()
    {
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