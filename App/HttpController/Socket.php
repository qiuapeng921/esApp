<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:31
 */

namespace App\HttpController;


class Socket extends Common
{
    /**
     * 默认的 websocket 测试页
     */
    public function index()
    {
        return $this->view('socket');
    }
}