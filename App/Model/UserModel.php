<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:30
 */

namespace App\Model;


use EasySwoole\Mysqli\Exceptions\ConnectFail;
use EasySwoole\Mysqli\Exceptions\PrepareQueryFail;
use EasySwoole\Mysqli\Mysqli;
use Throwable;

class UserModel extends BaseModel
{
    private static $table = 'user';

    /**
     * @param $userId
     * @param string $columns
     * @return Mysqli|mixed|null
     * @throws Throwable
     */
    public function getUserByUserId($userId, $columns = '*')
    {
        $result = $this->mysql()->where('user_id', $userId)->getOne(self::$table, $columns);
        return $result;
    }

    /**
     * @param $account
     * @return Mysqli|mixed
     * @throws ConnectFail
     * @throws PrepareQueryFail
     * @throws Throwable
     */
    public function searchUserByAccount($account)
    {
        $result = $this->mysql()->whereLike('account', "%" . $account . "%")->get(self::$table);
        return $result;
    }

    /**
     * @param $account
     * @return Mysqli|mixed|null
     * @throws Throwable
     */
    public function getUserByAccount($account)
    {
        $result = $this->mysql()->where('account', $account)->getOne(self::$table);
        return $result;
    }

    /**
     * @param $userIds
     * @param string $columns
     * @return Mysqli|mixed
     * @throws Throwable
     */
    public function getUserByUserIds($userIds, $columns = '*')
    {
        $result = $this->mysql()->whereIn('user_id', $userIds)->get(self::$table, null, $columns);
        return $result;
    }

    /**
     * @param $data
     * @return bool|int
     * @throws Throwable
     */
    public function createAccount($data)
    {
        $result = $this->mysql()->insert(self::$table, $data);
        return $result;
    }
}