<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 16:45
 */

namespace App\HttpController\Home;

use App\HttpController\Common;
use App\Model\GroupModel;
use App\Model\UserModel;
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
     * 我的好友
     * @return string|null
     * @throws Throwable
     */
    public function friend()
    {
        $result = (new UserFriendRepository())->getFriendByUserId(1);
        return $this->view('user.friend', ['result' => $result['data']]);
    }

    public function addFriend()
    {
        return $this->view('user.addFriend');
    }

    /**
     * 聊天界面
     * @return string|null
     * @throws Throwable
     */
    public function message()
    {
        $userId = $this->request()->getQueryParam('id');
        $result = (new UserModel())->getUserByUserId($userId, 'user_id,account,nick_name,image_url');
        return $this->view('user.message', ['result' => $result ?? []]);
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
     * 群组聊天
     */
    public function groupMessage()
    {
        $groupId = $this->request()->getQueryParam('group_id');
    }
}