<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 18:57
 */

namespace App\Model;


use Throwable;

class UserGroupMemberModel extends BaseModel
{

    private static $table = 'user_group_member';

    /**
     * 通过用户id获取分组id
     * @param $userId
     * @return array
     * @throws Throwable
     */
    public function getGroupIdsByUserId($userId)
    {
        return $this->mysql()->where('user_id', $userId)->getColumn(self::$table, null, 'group_id');
    }
}