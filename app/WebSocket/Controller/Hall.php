<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/29
 * Time: 13:48
 */

namespace App\WebSocket\Controller;

use App\Task\PushMessageTask;
use App\WebSocket\Service\StorageService;
use App\WebSocket\Service\UserService;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Socket\AbstractInterface\Controller;

class Hall extends Controller
{
    /**
     * 获取大厅聊天信息
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getMessage()
    {
        $fd = $this->caller()->getClient()->getFd();
        $message = (new StorageService())->getHallMessage();
        TaskManager::async(new PushMessageTask(['type' => 'hallMessage', 'data' => $message, "fromFd" => $fd]));
    }

    /**
     * 获取大厅在线用户
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOnlineUser()
    {
        $fd = $this->caller()->getClient()->getFd();
        $userService = new UserService();
        $userFd = $userService->getOnlineUserFd();
        $userList = [];
        foreach ($userFd as $fd) {
            $userList[] = $userService->getUserByFd($fd);
        }
        TaskManager::async(new PushMessageTask(['type' => 'onlineUser', 'data' => $userList, "fromFd" => $fd]));
    }
}