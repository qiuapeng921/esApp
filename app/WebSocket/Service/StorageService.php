<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/29
 * Time: 13:30
 */

namespace App\WebSocket\Service;

use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;

class StorageService extends BaseService
{
    /**
     * 储存大厅聊天记录
     * @param $nickName
     * @param $content
     * @return mixed
     * @throws PoolException
     */
    public function saveHallMessage($nickName, $content)
    {
        $score = $this->getRedis()->get('hallMessageKey') ?? 0;
        $this->getRedis()->set('hallMessageKey', $score + 1, 60 * 60 * 24);
        $this->getRedis()->zAdd("hallMessage", $score, json_encode(['nick_name' => $nickName, 'content' => $content]));
        return $this->getRedis()->setTimeout('hallMessage', 60 * 60 * 24);
    }

    /**
     * 获取大厅消息
     * @param $number
     * @return mixed
     * @throws PoolEmpty
     * @throws PoolException
     */
    public function getHallMessage($number = 30)
    {
        $max = $this->getRedis()->get('hallMessageKey');
        $min = $max - $number;
        return $this->getRedis()->zRangeByScore("hallMessage", $min, $max);
    }
}