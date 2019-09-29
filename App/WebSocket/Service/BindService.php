<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 17:58
 */

namespace App\WebSocket\Service;

use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

class BindService extends BaseService
{
    /**
     * 设置userId关联的fd
     * @param $userId
     * @param $fd
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function setUserIdFd($userId, $fd)
    {
        $this->getRedis()->hSet('userIdFd', $userId, $fd);
    }

    /**
     * 获取userId的关联的fd
     * @param $userId
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getUserIdFd($userId)
    {
        return $this->getRedis()->hGet('userIdFd', $userId);
    }

    /**
     * 删除userId关联的fd
     * @param $userId
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function deleteUserIdFd($userId)
    {
        return $this->getRedis()->hDel('userIdFd', $userId);
    }
}