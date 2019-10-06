<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/28
 * Time: 17:19
 */

namespace App\Model;


use EasySwoole\Mysqli\Mysqli;
use Throwable;

class GroupModel extends BaseModel
{
    private static $table = 'user_group';

    /**
     * 通过用户id获取群组
     * @param $userId
     * @return Mysqli|mixed
     * @throws Throwable
     */
    public function getGroupByUserId($userId)
    {
        return $this->mysql()->where('user_id', $userId)->get(self::$table, null, 'id,group_name,group_hand_url');
    }
}