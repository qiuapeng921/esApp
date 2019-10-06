<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/29
 * Time: 13:29
 */

namespace App\WebSocket\Service;

use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

class UserService extends BaseService
{
    /**
     * 添加在线用户集合
     * @param $fd
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function addOnlineUser($fd)
    {
        $this->getRedis()->sAdd('onlineUser', $fd);
    }

    /**
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getOnlineUserFd()
    {
        return $this->getRedis()->sMembers('onlineUser');
    }

    /**
     * 从在线集合中删除
     * @param $fd
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function deleteOnlineUser($fd)
    {
        $this->getRedis()->srem('onlineUser', $fd);
    }

    /**
     * 添加fd用户详情
     * @param $fd
     * @param $user
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function addFdUserInfo($fd, $user)
    {
        $this->getRedis()->hSet('userInfo', $fd, json_encode($user));
    }


    /**
     * 通过fd获取用户信息
     * @param $fd
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getUserByFd($fd)
    {
        return $this->getRedis()->hGet('userInfo', $fd);
    }

    /**
     * 通过fd删除用户信息
     * @param $fd
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function deleteFdUserInfo($fd)
    {
        $this->getRedis()->hDel('userInfo', $fd);
    }
}