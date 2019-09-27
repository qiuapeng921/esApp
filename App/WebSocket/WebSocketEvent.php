<?php

namespace App\WebSocket;

use App\Traits\RedisTrait;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException as PoolExceptionAlias;
use swoole_http_request;
use swoole_http_response;
use swoole_server;
use swoole_websocket_frame;
use swoole_websocket_server;

/**
 * Class WebSocketEvent
 * @package App\WebSocket
 */
class WebSocketEvent
{
    use RedisTrait;

    /**
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     * @throws PoolEmpty
     * @throws PoolExceptionAlias
     */
    public function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        $fd = $request->fd;
        $this->getRedis()->sAdd('fd', $fd);
        $user = $this->getRedis()->sMembers('fd');
        foreach ($user as $key => $item) {
            if ($fd == $item) {
                $server->push($item, '欢迎加入');
            } else {
                if (!$server->exist($item)) {
                    $this->getRedis()->srem('fd', $item);
                } else {
                    $server->push($item, "欢迎用户：{$fd}，加入聊天室");
                }
            }
        }
    }

    /**
     * 关闭事件
     * @param swoole_server $server
     * @param int $fd
     * @param int $reactorId
     * @throws PoolEmpty
     * @throws PoolExceptionAlias
     */
    public function onClose(swoole_server $server, int $fd, int $reactorId)
    {
        $this->getRedis()->srem('fd', $fd);
        $info = $server->getClientInfo($fd);
        if ($info && $info['websocket_status'] === WEBSOCKET_STATUS_FRAME) {
            if ($reactorId < 0) {
                echo "server close \n";
            }
        }
    }
}