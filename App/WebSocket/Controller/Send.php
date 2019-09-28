<?php


namespace App\WebSocket\Controller;

use App\Task\PushMessageTask;
use App\WebSocket\Bind;
use App\WebSocket\OnlineUser;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Socket\AbstractInterface\Controller;

class Send extends Controller
{
    /**
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function sendUser()
    {
        $fromFd = $args = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $userId = $args['user_id'];
        $content = $args['content'];
        $toFd = Bind::getInstance()->getUserIdFd($userId);
        TaskManager::async(new PushMessageTask(['type' => 'send', 'data' => $content, "fromFd" => $fromFd, 'toFd' => $toFd]));
    }

    public function sendGroup()
    {

    }

    /**
     * 向全体推送消息
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function sendAll()
    {
        $fd = $args = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $userInfo = OnlineUser::getInstance()->getUserByFd($fd);
        $userInfo = json_decode($userInfo, true);
        TaskManager::async(new PushMessageTask(['type' => 'sendAll', 'data' => $args['content'], 'fromFd' =>$fd]));
    }
}