<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:30
 */

namespace App\Model;


use Throwable;

class UserModel extends BaseModel
{
    protected $tableName = "user";

    /**
     * @param $userId
     * @param string $columns
     * @return UserModel|array|bool|null
     * @throws Throwable
     */
    public function getUserByUserId($userId, $columns = '*')
    {
        $result = $this->where('id', $userId)->field($columns)->get();
        if ($result){
            return $result->toArray();
        }
        return null;
    }

    /**
     * @param $account
     * @return UserModel|array|bool|null
     * @throws Throwable
     */
    public function searchUserByAccount($account)
    {
        $result = $this->where('account', "%" . $account . "%")->get();
        if ($result){
            return $result->toArray();
        }
        return null;
    }

    /**
     * @param $account
     * @return UserModel|array|bool|null
     * @throws Throwable
     */
    public function getUserByAccount($account)
    {
        $result = $this->where('account', $account)->get();
        if ($result){
            return $result->toArray();
        }
        return null;
    }

    /**
     * @param $userIds
     * @param string $columns
     * @return mixed
     * @throws Throwable
     */
    public function getUserByUserIds($userIds, $columns = '*')
    {
        $result = $this->where('user_id', "in", $userIds)->field($columns)->get();
        if ($result){
            return $result->toArray();
        }
        return null;
    }

    /**
     * @param $data
     * @return array
     * @throws Throwable
     */
    public function createAccount($data)
    {
        return $this->saveAll($data);
    }
}