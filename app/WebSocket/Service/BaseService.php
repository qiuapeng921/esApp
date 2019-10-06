<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/29
 * Time: 13:31
 */

namespace App\WebSocket\Service;


use App\Traits\RedisTrait;
use EasySwoole\Component\Singleton;

class BaseService
{
    use Singleton, RedisTrait;
}