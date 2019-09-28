<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 17:03
 */

namespace App\Repository;


use App\Model\UserFriendModel;
use App\Model\UserModel;
use Throwable;

class UserFriendRepository extends BaseRepository
{
    /**
     * 通过用户id获取通讯录信息
     * @param $userId
     * @return array
     * @throws Throwable
     */
    public function getFriendByUserId($userId)
    {
        $friendIds = (new UserFriendModel())->getFriendIdsByUerId($userId);
        if ($friendIds) {
            $result = (new UserModel())->getUserByUserIds($friendIds, 'user_id,nick_name,account,image_url');
            return $this->success($result);
        }
    }
}