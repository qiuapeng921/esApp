<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:11
 */

namespace App\Repository;


use App\Traits\MysqlTrait;
use App\Traits\RedisTrait;
use App\Traits\ResponseTrait;

class BaseRepository
{
    use MysqlTrait, RedisTrait, ResponseTrait;

    protected function parameterFilter(array $data)
    {
        foreach ($data as $key => $item) {
            $data[$key] = $item;
        }
        return $data;
    }
}