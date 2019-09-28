<?php

namespace App\HttpController;

class Index extends Common
{
    /**
     * @return string|void|null
     */
    public function index()
    {
        return $this->view('index', ['demo' => '欢迎使用 EsApp']);
    }
}