<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:57
 */

namespace App\Utility;


use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

/**
 * Class JWT
 * @package App\Utility
 */
class JWT
{
    /**
     * @var
     */
    protected $key = 'backend';

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * 生成签名
     * @param array $data
     * @return bool|string
     */
    public function jwtEncode(array $data = [])
    {
        if (empty($data)) {
            return false;
        }
        $time = time();
        // 过期时间
        $expiredTime = 7200;
        $token = [
            'iat' => $time, // 签发时间
            'exp' => $time + $expiredTime, // 过期时间
            'data' => $data
        ];
        return \Firebase\JWT\JWT::encode($token, $this->key);
    }

    /**
     * 解码签名
     * @param string $jwt
     * @return array
     */
    public function jwtDecode(string $jwt = '')
    {
        try {
            \Firebase\JWT\JWT::$leeway = 60;
            $decode = \Firebase\JWT\JWT::decode($jwt, $this->key, ['HS256']);
            return ['status' => 1, 'msg' => '解码签名成功', 'data' => (array)$decode];
        } catch (\InvalidArgumentException $e) {
            return ['status' => 0, 'msg' => '签名不能为空'];
        } catch (SignatureInvalidException $e) {
            return ['status' => 0, 'msg' => '签名错误'];
        } catch (ExpiredException $e) {
            return ['status' => 0, 'msg' => '签名已过期'];
        } catch (BeforeValidException $e) {
            return ['status' => 0, 'msg' => '其它错误'];
        } catch (\UnexpectedValueException $e) {
            return ['status' => 0, 'msg' => '签名无效'];
        } catch (\Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }
}