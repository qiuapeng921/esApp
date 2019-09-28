<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 18:43
 */

namespace App\Task;


use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

class MessageSyncTask extends AbstractAsyncTask
{
    /**
     * 执行任务的内容
     * @param mixed $taskData 任务数据
     * @param int $taskId 执行任务的task编号
     * @param int $fromWorkerId 派发任务的worker进程号
     * @param null $flags
     */
    protected function run($taskData, $taskId, $fromWorkerId, $flags = null)
    {
    }

    /**
     * 任务执行完的回调
     * @param mixed $result 任务执行完成返回的结果
     * @param int $task_id 执行任务的task编号
     */
    protected function finish($result, $task_id)
    {
    }
}