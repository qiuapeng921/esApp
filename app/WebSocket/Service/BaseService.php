<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/29
 * Time: 13:31
 */

namespace App\WebSocket\Service;

use App\Traits\RedisTrait;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Log\Logger;

class BaseService
{
    use Singleton, RedisTrait;
    /**
     * @var ServerManager
     */
    private $server;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct()
    {
        $this->server = ServerManager::getInstance()->getSwooleServer();
        $this->logger = new Logger();
    }
    /**
     * @param string $fd 接收者 fd
     * @param string $data
     * @param int $opCode
     * @param bool $finish
     * @return bool
     */
    public function push(string $fd, string $data, int $opCode = 1, bool $finish = true): bool
    {
        if (!$this->server->exist($fd)) {
            return false;
        }

        return $this->server->push($fd, $data, $opCode, $finish);
    }

    /**
     * 发送消息给指定的用户
     * @param int $receiver 接收者 fd
     * @param string $data
     * @param int $sender 发送者 fd
     * @return int
     */
    public function sendTo(int $receiver, string $data, int $sender = -1): int
    {
        $finish = true;
        $opCode = 1;
        $fromUser = $sender < 0 ? 'SYSTEM' : $sender;

        $this->logger->console("(私有的)The #{$fromUser} 向用户发送消息 #{$receiver}. 数据: {$data}");

        return $this->server->push($receiver, $data, $opCode, $finish) ? 1 : 0;
    }

    /**
     * 发送消息给在线所有用户
     * @param string $data
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToAll(string $data, int $sender = 0, int $pageSize = 50): int
    {
        $startFd = 0;
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;
        $this->logger->console("广播 #{$fromUser} 向所有用户发送消息. 消息: {$data}");

        while (true) {
            $fdList = $this->server->connection_list($startFd, $pageSize);

            if ($fdList === false || ($num = count($fdList)) === 0) {
                break;
            }

            $count += $num;
            $startFd = end($fdList);

            foreach ($fdList as $fd) {
                $info = $this->getClientInfo($fd);

                if (isset($info['websocket_status']) && $info['websocket_status'] > 0) {
                    $this->server->push($fd, $data);
                }
            }
        }

        return $count;
    }

    /**
     * 发送消息指定用户
     * @param string $data
     * @param array $receivers
     * @param array $excluded
     * @param int $sender
     * @param int $pageSize
     * @return int
     */
    public function sendToSome(string $data, array $receivers = [], array $excluded = [], int $sender = 0, int $pageSize = 50): int
    {
        $count = 0;
        $fromUser = $sender < 1 ? 'SYSTEM' : $sender;

        // to receivers
        if ($receivers) {
            $this->logger->console("广播 #{$fromUser} 给某个指定用户发送消息. 数据: {$data}");

            foreach ($receivers as $receiver) {
                if ($this->exist($receiver)) {
                    $count++;
                    $this->server->push($receiver, $data);
                }
            }

            return $count;
        }

        $startFd = 0;
        $excluded = $excluded ? (array)array_flip($excluded) : [];

        $this->logger->console("(broadcast)The #{$fromUser} send the message to everyone except some people. Data: {$data}");

        while (true) {
            $fdList = $this->server->connection_list($startFd, $pageSize);

            if ($fdList === false || ($num = count($fdList)) === 0) {
                break;
            }

            $count += $num;
            $startFd = end($fdList);

            foreach ($fdList as $fd) {
                if (isset($excluded[$fd])) {
                    continue;
                }

                $this->server->push($fd, $data);
            }
        }

        return $count;
    }

    /**
     * @param int $fd
     * @return bool
     */
    public function exist(int $fd): bool
    {
        return $this->server->exist($fd);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->server->connections);
    }


    /**
     * @return int
     */
    public function getErrorNo(): int
    {
        return $this->server->getLastError();
    }

    /**
     * @param int $fd
     * @return array
     */
    public function getClientInfo(int $fd): array
    {
        return $this->server->getClientInfo($fd);
    }

    /**
     * @param $fd
     * @param string $data
     * @return int
     */
    public function writeTo($fd, string $data): int
    {
        return $this->server->send($fd, $data) ? 0 : 1;
    }
}