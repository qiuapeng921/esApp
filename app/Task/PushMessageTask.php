<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 9:21
 */

namespace App\Task;

use App\WebSocket\Service\UserService;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

/**
 * 异步推送消息Task
 * Class PushMessageTask
 * @package App\Task
 */
class PushMessageTask extends AbstractAsyncTask
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
        $userService = new UserService();
        $onlineUser = $userService->getOnlineUserFd();
        $type = $taskData['type'];
        $data = $taskData['data'];
        $fromFd = $taskData['fromFd'] ?? 0;
        $toFd = $taskData['toFd'] ?? 0;
        $user = $userService->getUserByFd($fromFd);
        $user = json_decode($user, true);
        $message = ['nick_name' => $user['nick_name']];
        switch ($type) {
            case "send":
                echo "私发";
                $server->push($toFd, $this->responseJson($type, $data, $message));
                break;
            case "sendGroup":
                echo "群组发送";
                break;
            case "sendAll":
                echo "全员推送";
                foreach ($onlineUser as $fd) {
                    if (!$server->exist($fd)) {
                        // 删除在线用户fd
                        $userService->deleteOnlineUser($fd);
                        // 删除fd关联的用户信息
                        $userService->deleteFdUserInfo($fd);
                        continue;
                    }
                    $server->push($fd, $this->responseJson($type, $data, $message));
                }
                break;
            case "join":
                echo "加入大厅";
                foreach ($onlineUser as $fd) {
                    if (!$server->exist($fd)) {
                        // 删除在线用户fd
                        $userService->deleteOnlineUser($fd);
                        // 删除fd关联的用户信息
                        $userService->deleteFdUserInfo($fd);
                        continue;
                    }
                    if ($fd == $fromFd) {
                        $server->push($fromFd, $this->responseJson($type, $data));
                    } else {
                        $server->push($fd, $this->responseJson($type, $data, $message));
                    }
                }
                break;
            case "leave":
                echo "离开大厅";
                foreach ($onlineUser as $fd) {
                    if (!$server->exist($fd)) {
                        // 删除在线用户fd
                        $userService->deleteOnlineUser($fd);
                        // 删除fd关联的用户信息
                        $userService->deleteFdUserInfo($fd);
                        continue;
                    }
                    $server->push($fd, $this->responseJson($type, $data));
                }
                break;
            case "hallMessage":
                echo "大厅消息";
                $server->push($fromFd, $this->responseJson($type, $data));
                break;
            case "onlineUser":
                echo "推送在线用户";
                foreach ($onlineUser as $fd) {
                    if (!$server->exist($fd)) {
                        // 删除在线用户fd
                        $userService->deleteOnlineUser($fd);
                        // 删除fd关联的用户信息
                        $userService->deleteFdUserInfo($fd);
                        continue;
                    }
                    $server->push($fd, $this->responseJson($type, $data));
                }
                break;
            default:
                echo "默认";
                break;
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

    /**
     * 组装返回客户端
     * @param $type
     * @param $data
     * @param array $option
     * @return false|string
     */
    private function responseJson($type, $data, $option = [])
    {
        return json_encode(['type' => $type, 'data' => $data, 'option' => $option]);
    }
}