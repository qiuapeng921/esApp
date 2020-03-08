<?php

namespace App\WebSocket;

use Swoole\Http\Request;
use Swoole\WebSocket\Server;

/**
 * Class WebSocketEvent
 * @package App\WebSocket
 */
class WebSocketEvent
{
    /**
     * @param Server $server
     * @param Request $request
     */
    public static function onOpen(Server $server, Request $request)
    {
        $server->push($request->fd, "welcome to you");
    }

    /**
     * 关闭事件
     * @param Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public static function onClose(Server $server, int $fd, int $reactorId)
    {
        $info = $server->connection_info($fd);
        if (isset($info['websocket_status']) && $info['websocket_status'] !== 0) {
            dd($fd, "断开连接");
        }
    }
}