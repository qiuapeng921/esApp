<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:37
 */

namespace App\WebSocket\Controller;

use App\Model\BaseModel;
use App\Task\BroadcastTask;
use App\Traits\RedisTrait;
use App\WebSocket\OnlineUser;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;

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

    /**
     * 向全体推送消息
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function sendToAll()
    {
        $fd = $args = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $userInfo = OnlineUser::getInstance()->getUserByFd($fd);
        $userInfo = json_decode($userInfo, true);
        TaskManager::async(new BroadcastTask(['type' => 'sendAll', 'data' => $args['content'], 'fromFd' => $userInfo['account']]));
    }
}