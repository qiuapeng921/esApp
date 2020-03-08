<?php

namespace App\HttpController;

use EasySwoole\Utility\Random;

class Index extends Common
{
    /**
     * @return string|void|null
     */
    public function index()
    {
        return $this->view('index', ['demo' => '欢迎使用 EsApp']);
    }

    /**
     * 大厅
     * @return string|null
     */
    public function hall()
    {
        $id = Random::number();
        return $this->view('hall', ['id' => $id]);
    }
}