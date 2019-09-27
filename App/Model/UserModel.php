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
     * @param $data
     * @return bool|int
     */
    public function createAccount($data)
    {
        try {
            $result = $this->mysql()->insert(self::$table, $data);
            return $result;
        } catch (ConnectFail $e) {
            dd($e->getMessage());
        } catch (PrepareQueryFail $e) {
            dd($e->getMessage());
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }
}