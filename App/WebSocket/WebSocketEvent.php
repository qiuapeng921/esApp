<?php

namespace App\WebSocket;

use App\Model\UserModel;
use App\Task\BroadcastTask;
use App\Utility\JWT;

use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\Utility\Random;

use swoole_http_request;
use swoole_http_response;
use swoole_server;
use swoole_websocket_frame;
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
                // 设置fd关联的userId
                Bind::getInstance()->setFdUserId($fd, $params['id']);
                //设置userId关联的fd
                Bind::getInstance()->setUserIdFd($params['id'], $fd);
                // 添加用户信息集合中
                OnlineUser::getInstance()->addFdUserInfo($fd, $user);
            }
        }
        // 插入在线集合
        OnlineUser::getInstance()->addOnlineUser($fd);
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
            // 刪除fd用戶信息
            OnlineUser::getInstance()->deleteFdUserInfo($fd);
            // 通过fd获取fd绑定的用户信息
            $user = OnlineUser::getInstance()->getUserByFd($fd);
            $user = json_decode($user, true);
            // 删除fd关联的userId
            Bind::getInstance()->deleteUserIdFd($user['user_id']);
            // 删除userId关联的fd
            Bind::getInstance()->deleteFdUserId($fd);
        }
    }
}