<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/3
 * Time: 10:43
 */

namespace App\Traits;

use Swoole\Coroutine\Channel;
use EasySwoole\HttpClient\HttpClient;

/**
 * Class RequestTraits
 * @package App\Traits
 */
trait RequestTraits
{
    /**
     * 协程客户端
     * @param $url
     * @param array $header
     * @param array $postData
     * @param string $method
     * @param int $time
     * @return mixed
     */
    public function httpRequest($url, $header = [], $postData = [], $method = "get", $time = 0)
    {
        $chan = new Channel(10);
        go(function () use ($chan, $url, $postData, $header, $method, $time) {
            $client = new HttpClient($url);
            if ($header) {
                $client->setHeaders($header);
            }
            if ($time) {
                $client->setTimeout($time);
            }
            if ($method == "post") {
                $chan->push($client->post($postData)->getBody());
            } else if ($method == "postJson") {
                $chan->push($client->postJson(json_encode($postData))->getBody());
            } else if ($method == "postXml") {
                $chan->push($client->postXml(arrayToXml($postData))->getBody());
            } else {
                $chan->push($client->get()->getBody());
            }
        });
        return $chan->pop();
    }
}