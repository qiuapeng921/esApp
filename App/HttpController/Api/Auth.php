<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:07
 */

namespace App\HttpController\Api;


use App\HttpController\Common;
use App\Repository\UserRepository;
use Throwable;

/**
 * Class Auth
 * @package App\HttpController\Api
 */
class Auth extends Common
{
    /**
     * 用户登录
     * @return bool
     * @throws Throwable
     */
    public function login()
    {
        $request = $this->request()->getParsedBody();
        $response = (new UserRepository())->handleLogin($request);
        if ($response['code'] == 200) {
            $this->response()->setCookie('token', $response['data']['token'], 7);
            $this->response()->setCookie('user_id', $response['data']['info']['user_id'], 7);
            $this->response()->setCookie('account', $response['data']['token']['account'], 7);
        }
        return $this->responseJson($response);
    }

    /**
     * 用户注册
     * @return bool
     * @throws Throwable
     */
    public function register()
    {
        $request = $this->request()->getParsedBody();
        $response = (new UserRepository())->handleRegister($request);
        return $this->responseJson($response);
    }

    public function logout()
    {

    }
}