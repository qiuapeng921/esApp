<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:22
 */

namespace App\WebSocket;

use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Client\WebSocket;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;

/**
 * 消息解析器
 * @package App\WebSocket
 */
class SocketParser implements ParserInterface
{
    /**
     * decode
     * @param string $raw 客户端原始消息
     * @param WebSocket $client WebSocket Client 对象
     * @return Caller         Socket  调用对象
     */
    public function decode($raw, $client): ?Caller
    {
        // 解析 客户端原始消息
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            echo "decode message error! \n";
            return null;
        }

        // new 调用者对象
        $caller = new Caller();
        $class = '\\App\\WebSocket\\' . ucfirst($data['class'] ?? 'Index');
        $caller->setControllerClass($class);
        // 设置被调用的方法
        $caller->setAction($data['action'] ?? 'index');
        // 检查是否存在args
        if (!empty($data['content'])) {
            // content 无法解析为array 时 返回 content => string 格式
            $args = is_array($data['content']) ? $data['content'] : ['content' => $data['content']];
        }

        // 设置被调用的Args
        $caller->setArgs($args ?? []);
        return $caller;
    }

    /**
     * encode
     * @param Response $response Socket Response 对象
     * @param WebSocket $client WebSocket Client 对象
     * @return string             发送给客户端的消息
     */
    public function encode(Response $response, $client): ?string
    {
        return $response->getMessage();
    }
}