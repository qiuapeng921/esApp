<?php


namespace App\WebSocket\Controller;

use App\Task\MessageSyncTask;
use App\Task\PushMessageTask;
use App\WebSocket\Service\BindService;

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
        $toFd = BindService::getInstance()->getUserIdFd($userId);
        TaskManager::async(new PushMessageTask(['type' => 'send', 'data' => $content, "fromFd" => $fromFd, 'toFd' => $toFd]));

    }

    public function sendGroup()
    {

    }

    /**
     * 向全体推送消息
     */
    public function sendAll()
    {
        $fd = $args = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        // 推送消息给所有用户
        TaskManager::async(new PushMessageTask(['type' => 'sendAll', 'data' => $args['content'], 'fromFd' => $fd]));
        // 异步存储消息记录
        TaskManager::async(new MessageSyncTask(['type' => 'sendAll', 'data' => $args['content'], "fromFd" => $fd]));
    }
}