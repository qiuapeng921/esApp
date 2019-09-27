<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:37
 */

namespace App\WebSocket;

use App\Traits\RedisTrait;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;

/**
 * Class Index
 * @package App\WebSocket
 */
class Index extends Controller
{
    use RedisTrait;

    public function hello()
    {
        $this->response()->setMessage('call hello with arg:' . json_encode($this->caller()->getArgs()));
    }

    /**
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function sendToAll()
    {
        $user = $this->getRedis()->sMembers('fd');
        $args = $this->caller()->getArgs();
        TaskManager::async(function () use ($user, $args) {
            $server = ServerManager::getInstance()->getSwooleServer();
            foreach ($user as $item) {
                $server->push($item, $args['content']);
            }
        });
    }
}