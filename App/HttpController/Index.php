<?php

namespace App\HttpController;

class Index extends Common
{
    public function index()
    {
        return $this->view('index', ['demo' => '欢迎使用 EsApp']);
    }
}