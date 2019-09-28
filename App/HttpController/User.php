<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 16:45
 */

namespace App\HttpController;

use App\Model\GroupModel;
use App\Repository\UserFriendRepository;
use Throwable;

class User extends Common
{
    /**
     * 登陆
     * @return string|null
     */
    public function login()
    {
        return $this->view('login');
    }

    /**
     * 注册
     * @return string|null
     */
    public function register()
    {
        return $this->view('register');
    }

    /**
     * 默认的 websocket 测试页
     */
    public function socket()
    {
        $id = $this->request()->getQueryParam('id') ?? 0;
        return $this->view('user.socket', ['id' => $id]);
    }

    /**
     * 我的好友
     * @return string|null
     * @throws Throwable
     */
    public function friend()
    {
        $result = (new UserFriendRepository())->getFriendByUserId(1);
        return $this->view('user.friend', ['result' => $result['data']]);
    }

    /**
     * 我的分组
     * @return string|null
     * @throws Throwable
     */
    public function group()
    {
        $result = (new GroupModel())->getGroupByUserId(1);
        return $this->view('user.group', ['result' => $result]);
    }

    /**
     *
     */
    public function groupMessage()
    {
        $groupId = $this->request()->getQueryParam('group_id');
    }

    /**
     * 聊天界面
     * @return string|null
     * @throws Throwable
     */
    public function message()
    {
        $userId = $this->request()->getQueryParam('id');
        $result = (new GroupModel())->getGroupByUserId($userId);
        return $this->view('user.message', ['result' => $result]);
    }
}