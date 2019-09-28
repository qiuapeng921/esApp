<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 9:21
 */

namespace App\Task;


use App\WebSocket\OnlineUser;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

class BroadcastTask extends AbstractAsyncTask
{
    /**
     * 执行任务的内容
     * @param mixed $taskData 任务数据
     * @param int $taskId 执行任务的task编号
     * @param int $fromWorkerId 派发任务的worker进程号
     * @param null $flags
     * @throws PoolEmpty
     * @throws PoolException
     */
    protected function run($taskData, $taskId, $fromWorkerId, $flags = null)
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $onlineUser = OnlineUser::getInstance()->getOnlineUserFd();
        $type = $taskData['type'];
        $data = $taskData['data'];
        $formFd = $taskData['fromFd'] ?? 0;
        foreach ($onlineUser as $fd) {
            if (!$server->exist($fd)) {
                // 删除在线用户fd
                OnlineUser::getInstance()->deleteOnlineUser($fd);
                // 删除fd关联的用户信息
                OnlineUser::getInstance()->deleteFdUserInfo($fd);
                continue;
            }
            $server->push($fd, $this->response($type, $data, $formFd));
        }
    }

    /**
     * 任务执行完的回调
     * @param mixed $result 任务执行完成返回的结果
     * @param int $task_id 执行任务的task编号
     */
    protected function finish($result, $task_id)
    {
        print_r('推送完成' . PHP_EOL);
    }

    private function response($type, $data, $fromFd)
    {
        return json_encode(['type' => $type, 'data' => $data, 'fromFd' => $fromFd]);
    }
}