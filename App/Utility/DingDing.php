<?php
/**
 * 钉钉通知
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/3/29 9:40
 */

namespace App\Utility;

use Swoole\Coroutine\Channel;
use EasySwoole\EasySwoole\Config;
use EasySwoole\HttpClient\HttpClient;

/**
 * Class DingDing
 * @package App\Utility
 */
class DingDing
{
    private $web_hook;

    /**
     * DingDing constructor.
     */
    public function __construct()
    {
        if (!$this->web_hook) {
            $this->web_hook = Config::getInstance()->getConf('AliYun.webHook');
        }
    }

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     */
    public function sendText($content, $mobiles = [], $isAtAll = false)
    {
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content
            ],
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $isAtAll
            ]
        ];
        $this->request($data);
    }

    /**
     * 发送链接消息
     * @param string $title 消息标题
     * @param string $text 消息内容。如果太长只会部分展示
     * @param string $messageUrl 点击消息跳转的URL
     * @param string $picUrl 图片URL
     */
    public function sendLink($title, $text, $messageUrl, $picUrl = '')
    {
        $data = [
            'msgtype' => 'link',
            'link' => [
                'title' => $title,
                'text' => $text,
                'messageUrl' => $messageUrl,
                'picUrl' => $picUrl,
            ]
        ];
        $this->request($data);
    }

    /**
     * 发送Markdown消息
     * @param string $title 首屏会话透出的展示内容
     * @param string $text markdown格式的消息
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     */
    public function sendMarkdown($title, $text, $mobiles = [], $isAtAll = false)
    {
        $data = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $text,
            ],
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $isAtAll
            ]
        ];
        $this->request($data);
    }

    /**
     * @param $postData
     * @return mixed
     */
    private function request($postData)
    {
        $chan = new Channel(1);
        go(function () use ($chan, $postData) {
            $client = new HttpClient($this->web_hook);
            $client->setHeaders(["Content-Type" => "application/json"]);
            $chan->push($client->postJson(json_encode($postData)));
        });
        return $chan->pop();
    }
}
