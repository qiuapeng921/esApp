<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:37
 */

namespace App\WebSocket\Controller;

use App\Traits\RedisTrait;
use EasySwoole\Socket\AbstractInterface\Controller;

/**
 * Class Index
 * @package App\WebSocket
 */
class Index extends Controller
{
    use RedisTrait;

    /**
     * 心跳检测
     */
    public function heartbeat()
    {
        $this->response()->setMessage('PONG');
    }
}