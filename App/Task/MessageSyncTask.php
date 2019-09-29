<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 18:43
 */

namespace App\Task;

use App\WebSocket\Service\UserService;
use App\WebSocket\Service\StorageService;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

/**
 * 异步存储消息Task
 * Class MessageSyncTask
 * @package App\Task
 */
class MessageSyncTask extends AbstractAsyncTask
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
        $type = $taskData['type'];
        $data = $taskData['data'];
        $fromFd = $taskData['fromFd'];
        $userService = new UserService();
        $storageService = new StorageService();
        $user = $userService->getUserByFd($fromFd);
        $user = json_decode($user, true);
        switch ($type) {
            case "send":
                echo "单聊";
                break;
            case "sendGroup":
                echo "群组发送";
                break;
            case "sendAll":
                echo "群组发送";
                $storageService->saveHallMessage($user['nick_name'], $data);
                break;
            default:
                echo "默认";
                echo 'default';
        }
    }

    /**
     * 任务执行完的回调
     * @param mixed $result 任务执行完成返回的结果
     * @param int $task_id 执行任务的task编号
     */
    protected function finish($result, $task_id)
    {
        print_r('消息同步完成' . PHP_EOL);
    }
}