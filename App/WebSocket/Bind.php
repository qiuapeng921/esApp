<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 17:58
 */

namespace App\WebSocket;


use App\Traits\RedisTrait;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Singleton;

class Bind
{
    use Singleton, RedisTrait;

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

    /**
     * 设置fd关联的userId
     * @param $fd
     * @param $userId
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function setFdUserId($fd, $userId)
    {
        $this->getRedis()->hSet('fdUserId', $fd, $userId);
    }

    /**
     * 获取fd的关联的userId
     * @param $fd
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getFdUserId($fd)
    {
        return $this->getRedis()->hGet('fdUserId', $fd);
    }

    /**
     * 删除fd关联的userId
     * @param $fd
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function deleteFdUserId($fd)
    {
        return $this->getRedis()->hDel('fdUserId', $fd);
    }
}