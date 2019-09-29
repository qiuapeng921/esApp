<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:11
 */

namespace App\Repository;

use App\Model\UserModel;
use App\Utility\JWT;
use Throwable;

class UserRepository extends BaseRepository
{
    /**
     * 处理用户登录
     * @param $request
     * @return array
     * @throws Throwable
     */
    public function handleLogin($request)
    {
        $data = $this->parameterFilter($request);
        $account = $data['account'];
        if (!$account) {
            return $this->fail(null, "账号不能为空");
        }
        $userInfo = (new UserModel())->getUserByAccount($account);
        if (!$userInfo) {
            return $this->fail(null, "账号不存在");
        }
        if (!$userInfo['status']) {
            return $this->fail(null, "该账户已被锁定");
        }
        if (!validateHash($data['password'], $userInfo['password'])) {
            return $this->fail(null, "用户名密码不匹配");
        }
        unset($userInfo['password']);
        $token = (new JWT())->jwtEncode($userInfo);
        $this->getRedis()->set(sprintf('userToken_%s', $userInfo['user_id']), $token);
        $this->getRedis()->set(sprintf('userInfo_%s', $userInfo['user_id']), json_encode($userInfo));
        $result = [
            'info' => $userInfo,
            'token' => $token
        ];
        return $this->success($result);
    }

    /**
     * 处理注册
     * @param $request
     * @return array
     * @throws Throwable
     */
    public function handleRegister($request)
    {
        $data = $this->parameterFilter($request);
        $account = $data['account'];
        if (!$account) {
            return $this->fail(null, "账号不能为空");
        }
        $password = $data['password'];
        if (!$password) {
            return $this->fail(null, "密码不能为空");
        }
        $userModel = new UserModel();
        $user = $userModel->getUserByAccount($account);
        if ($user) {
            return $this->fail(null, "此用户已存在");
        }

        $data = [
            'account' => $account,
            'password' => makeHash($password),
            'create_time' => time()
        ];
        $result = $userModel->createAccount($data);
        if (!$result) {
            return $this->fail(null, "注册失败");
        }
        return $this->success($request, 200, "注册成功", 1);
    }
}