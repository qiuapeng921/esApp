<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 17:01
 */

namespace App\Model;


use Throwable;

class UserFriendModel extends BaseModel
{
    private static $table = 'user_friend';

    /**
     * @param $userId
     * @return array
     * @throws Throwable
     */
    public function getFriendIdsByUerId($userId)
    {
        return $this->mysql()->where('user_id', $userId)->getColumn(self::$table, 'friend_id');
    }
}