<?php

namespace App\Traits;

use App\Constants\ErrorConst;
use App\Constants\SuccessConst;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait ResponseTrait
{
    /**
     * 拼装成功数据
     * @param null $data
     * @param int $code
     * @param string $message
     * @param int $count
     * @return array
     */
    public function success($data = null, $code = null, $message = null, $count = 0)
    {
        return [
            'code' => $code ? $code : 200,
            'message' => $message ? $message : SuccessConst::getMessage($code),
            'count' => $count,
            'data' => $data,
        ];
    }

    /**
     * 异常返回
     * @param int $code
     * @param string $message
     * @return array
     */
    public function fail($code = null, $message = null)
    {
        return [
            'code' => $code ? $code : 100,
            'message' => $message ? $message : ErrorConst::getMessage($code)
        ];
    }
}
