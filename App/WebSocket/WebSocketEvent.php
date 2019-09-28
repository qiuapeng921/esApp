<?php

namespace App\WebSocket;

use App\Model\UserModel;

use App\Task\PushMessageTask;
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
        if (isset($params['id']) && !empty($params['id'])) {
            $user = (new UserModel())->getUserByUserId($params['id'], 'user_id,account');
            if ($user) {
                // 将fd和用户id绑定
                $server->bind($fd, $params['id']);
                //设置userId关联的fd
                Bind::getInstance()->setUserIdFd($params['id'], $fd);
            }
        } else {
            $user = ['user_id' => Random::number(), 'nick_name' => Random::character()];
        }
        // 添加用户信息集合中
        OnlineUser::getInstance()->addFdUserInfo($fd, $user);
        // 插入在线集合
        OnlineUser::getInstance()->addOnlineUser($fd);
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
            OnlineUser::getInstance()->deleteOnlineUser($fd);

            // 通过fd获取fd绑定的用户信息
            $user = OnlineUser::getInstance()->getUserByFd($fd);
            $user = json_decode($user, true);
            // 删除fd关联的userId
            Bind::getInstance()->deleteUserIdFd($user['user_id']);
            // 刪除fd用戶信息
            OnlineUser::getInstance()->deleteFdUserInfo($fd);
            TaskManager::async(new PushMessageTask(['type' => 'leave', 'data' => $user['nick_name'], "fromFd" => $fd]));
        }
    }
}