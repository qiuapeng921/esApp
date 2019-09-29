<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 18:35
 */

namespace App\HttpController\Api;

use App\HttpController\Common;
use App\Model\GroupModel;
use App\Model\UserModel;
use App\Repository\UserFriendRepository;
use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;
use Throwable;

class User extends Common
{
    /**
     * 用户详情
     * @return bool
     * @throws Throwable
     */
    public function info()
    {
        $userId = $this->request()->getParsedBody("user_id");
        $result = (new UserModel())->getUserByUserId($userId);
        return $this->responseJson($this->success($result, 200, 'success', 1));
    }

    /**
     * 我的群组
     * @return bool
     * @throws Throwable
     */
    public function group()
    {
        $userId = $this->request()->getParsedBody("user_id");
        $result = (new GroupModel())->getGroupByUserId($userId);
        return $this->responseJson($this->success($result, 200, 'success', count($result)));
    }

    /**
     * 我的好友
     * @return bool
     * @throws Throwable
     */
    public function friend()
    {
        $userId = $this->request()->getParsedBody("user_id");
        $result = (new UserFriendRepository())->getFriendByUserId($userId);
        return $this->responseJson($result);
    }

    /**
     * @return bool
     * @throws Throwable
     * @throws ConnectFail
     * @throws PrepareQueryFail
     */
    public function searchFriend()
    {
        $account = $this->request()->getParsedBody("account");
        $result = (new UserModel())->searchUserByAccount($account);
        if (!$result) {
            return $this->responseJson($this->fail(null, '用戶不存在'));
        }
        return $this->responseJson($this->success($result));
    }

    /**
     * 添加好友
     */
    public function addFriend()
    {
        echo 1111111111;
        $fd = $this->request()->getSwooleRequest()->fd;
        dd($fd);
    }

    public function delFriend()
    {

    }

    /**
     * 审核申请
     */
    public function reviewApplication()
    {

    }
}