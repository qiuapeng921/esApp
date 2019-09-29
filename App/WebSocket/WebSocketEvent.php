<?php

namespace App\WebSocket;

use App\Model\UserModel;

use App\Task\PushMessageTask;
use App\WebSocket\Service\BindService;
use App\WebSocket\Service\UserService;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Utility\Random;

use swoole_http_request;
use swoole_server;
use swoole_websocket_server;
use Throwable;

/**
 * Class WebSocketEvent
 * @package App\WebSocket
 */
class WebSocketEvent
{
    /**
     * 打开了一个链接
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     * @throws PoolEmpty
     * @throws PoolException
     * @throws Throwable
     */
    public static function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        // 为用户分配身份并插入到用户表
        $fd = $request->fd;
        $params = $request->get;
        if (isset($params['token']) && !empty($params['token'])) {
            $user = (new UserModel())->getUserByUserId($params['id'], 'user_id,account');
            if ($user) {
                // 将fd和用户id绑定
                $server->bind($fd, $params['id']);
                //设置userId关联的fd
                (new BindService())->setUserIdFd($params['id'], $fd);
            }
        } else {
            $user = ['user_id' => Random::number(), 'nick_name' => Random::character()];
        }
        $userService = new UserService();
        // 添加用户信息集合中
        $userService->addFdUserInfo($fd, $user);
        // 插入在线集合
        $userService->addOnlineUser($fd);
        // 推送加入
        TaskManager::async(new PushMessageTask(['type' => 'join', 'data' => $user['nick_name'], "fromFd" => $fd]));
    }

    /**
     * 关闭事件
     * @param swoole_server $server
     * @param int $fd
     * @param int $reactorId
     * @throws PoolEmpty
     * @throws PoolException
     */
    public static function onClose(swoole_server $server, int $fd, int $reactorId)
    {
        $info = $server->connection_info($fd);
        if (isset($info['websocket_status']) && $info['websocket_status'] !== 0) {
            // 从集合中删除当前已退出的fd
            $userService = new UserService();
            $userService->deleteOnlineUser($fd);

            // 通过fd获取fd绑定的用户信息
            $user = $userService->getUserByFd($fd);
            $user = json_decode($user, true);
            // 删除fd关联的userId
            (new BindService())->deleteUserIdFd($user['user_id']);
            // 刪除fd用戶信息
            $userService->deleteFdUserInfo($fd);
            TaskManager::async(new PushMessageTask(['type' => 'leave', 'data' => $user['nick_name']]));

            $userFd = $userService->getOnlineUserFd();
            $userList = [];
            foreach ($userFd as $fd) {
                $userList[] = $userService->getUserByFd($fd);
            }
            TaskManager::async(new PushMessageTask(['type' => 'onlineUser', 'data' => $userList, "fromFd" => $fd]));
        }
    }
}