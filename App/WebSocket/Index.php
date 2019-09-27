<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/25
 * Time: 15:37
 */

namespace App\WebSocket;

use App\Model\BaseModel;
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

    public function done()
    {
        $this->response()->setMessage('done');
    }

    /**
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function online()
    {
        $user = $this->getRedis()->sMembers('fd');
        $args = $this->caller()->getArgs();
        $data = ['user' => $user, 'online' => $args['content']];
        $this->response()->setMessage(json_encode($data));
    }

    /**
     *
     */
    public function sendToAll()
    {
        $args = $this->caller()->getArgs();
        TaskManager::async(function () use ($user, $args) {
            $server = ServerManager::getInstance()->getSwooleServer();
            $server->sendto('127.0.0.1',9501,$args['content']);
//            foreach ($user as $item) {
//                $server->push($item, $args['content']);
//            }
        });
    }
}