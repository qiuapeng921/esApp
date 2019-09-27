<?php

namespace App\WebSocket;

use App\Traits\RedisTrait;
use App\Utility\JWT;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
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

    private $userInfo;

    /**
     * 握手事件
     * @param swoole_http_request $request
     * @param swoole_http_response $response
     * @return bool
     * @throws PoolEmpty
     * @throws PoolException
     * @throws ModifyError
     */
    public function onHandShake(swoole_http_request $request, swoole_http_response $response)
    {
        // TODO 此处自定义握手规则 返回 false 时中止握手
        if (!$this->customHandShake($request, $response)) {
            $response->end();
            return false;
        }

        // TODO 此处是  RFC规范中的WebSocket握手验证过程 必须执行 否则无法正确握手
        if ($this->secWebsocketAccept($request, $response)) {
            $response->end();

            $fd = $request->fd;
            $server = ServerManager::getInstance()->getSwooleServer();
            // 将fd和userId绑定
            $server->bind($fd, $this->userInfo->user_id);
            // 将userId绑定的fd存入共享内存
            setContext(sprintf("user_%s", $this->userInfo->user_id), $fd);
            $redis = $this->getRedis();
            $redis->sAdd('onlineUser', json_encode($this->userInfo));
            $user = $redis->sMembers('onlineUser');
            dd($user);
            // 向在线用户推送在线人数
            $server->push($fd, '欢迎加入');
//            foreach ($user as $key => $item) {
//                if (!$server->exist($item)) {
//                    $redis->srem('fd', $item);
//                }
//                $server->push($item, (string)$redis->sMembers('fd'));
//            }
            return true;
        }

        $response->end();
        return false;
    }

    /**
     * 自定义握手事件
     * @param swoole_http_request $request
     * @param swoole_http_response $response
     * @return bool
     */
    private function customHandShake(swoole_http_request $request, swoole_http_response $response): bool
    {
        $params = $request->get;
        if (!isset($params['token'])) {
            return false;
        }

        $token = $params['token'];
        $result = (new JWT())->jwtDecode($token);
        if (!$result['status']) {
            return false;
        }
        $this->userInfo = $result['data']['data'];
        return true;
    }

    /**
     * 关闭事件
     * @param swoole_server $server
     * @param int $fd
     * @param int $reactorId
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function onClose(swoole_server $server, int $fd, int $reactorId)
    {
//        $this->getRedis()->srem('onlineUser', $fd);
        $info = $server->getClientInfo($fd);
        if ($info && $info['websocket_status'] === WEBSOCKET_STATUS_FRAME) {
            if ($reactorId <= 0) {
                echo "server close \n";
            }
        }
    }

    /**
     * RFC规范中的WebSocket握手验证过程
     * 以下内容必须强制使用
     * @param swoole_http_request $request
     * @param swoole_http_response $response
     * @return bool
     */
    private function secWebsocketAccept(swoole_http_request $request, swoole_http_response $response): bool
    {
        if (!isset($request->header['sec-websocket-key'])) {
            // TODO 需要 Sec-WebSocket-Key 如果没有拒绝握手
            var_dump('shake fai1 3');
            return false;
        }
        if (0 === preg_match('#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header['sec-websocket-key']) || 16 !== strlen(base64_decode($request->header['sec-websocket-key']))) {
            // TODO 不接受握手
            var_dump('shake fai1 4');
            return false;
        }

        $key = base64_encode(sha1($request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
        $headers = array(
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Accept' => $key,
            'Sec-WebSocket-Version' => '13',
            'KeepAlive' => 'off',
        );

        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        // TODO 发送验证后的header
        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        // TODO 接受握手 还需要101状态码以切换状态
        $response->status(101);
        var_dump('shake success at fd :' . $request->fd);
        return true;
    }
}