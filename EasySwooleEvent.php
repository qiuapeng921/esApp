<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Exception\ExceptionHandler;
use App\Utility\Blade;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use App\WebSocket\Parser\SocketParser;
use App\WebSocket\WebSocketEvent;
use Dotenv\Dotenv;
use EasySwoole\Component\Di;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\RedisPoolException;
use EasySwoole\Socket\Dispatcher;
use EasySwoole\Template\Render;
use Exception;
use Throwable;


class EasySwooleEvent implements Event
{
    /**
     * @throws \EasySwoole\Pool\Exception\Exception
     * @throws Exception
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 载入项目 Conf 文件夹中所有的配置文件
        self::loadConf(EASYSWOOLE_ROOT . '/config.php');
        // 异常捕捉
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class, 'handle']);

        MysqlPool::createObject();
        self::initRedisPoll();
    }

    /**
     * @param EventRegister $register
     * @throws Exception
     */
    public static function mainServerCreate(EventRegister $register)
    {
        // TODO 加载webSocket
        self::initWebSocket($register);

        // TODO 模板
        Render::getInstance()->getConfig()->setRender(new Blade());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());
    }

    /**
     * @param EventRegister $register
     * @throws \EasySwoole\Socket\Exception\Exception
     * @throws Exception
     */
    public static function initWebSocket(EventRegister $register)
    {
        // 注册服务事件
        $register->add(EventRegister::onOpen, [WebSocketEvent::class, 'onOpen']);
        $register->add(EventRegister::onClose, [WebSocketEvent::class, 'onClose']);

        // 收到用户消息时处理
        $conf = new \EasySwoole\Socket\Config;
        $conf->setType($conf::WEB_SOCKET);
        $conf->setParser(new SocketParser);
        $dispatch = new Dispatcher($conf);
        $register->set(EventRegister::onMessage, function (\swoole_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return bool
     * @throws Throwable
     */
    public static function onRequest(Request $request, Response $response): bool
    {
        $origin = isset($request->getHeader("Origin")[0]) ?? '';
        $allow_origin = config('ORIGIN');
        if (in_array($origin, $allow_origin)) {
            $response->withHeader('Access-Control-Allow-Origin', $origin);
        } else {
            $response->withHeader('Access-Control-Allow-Origin', "*");
        }
        $response->withHeader('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type,Content-Length,Authorization, Accept,X-Requested-With,token,Keep-Alive');
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(Status::CODE_OK);
            return false;
        }
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
    }

    /**
     * @param $ConfPath
     * @throws Exception
     */
    public static function loadConf($ConfPath)
    {
        if (file_exists(EASYSWOOLE_ROOT . '/.env')) {
            Dotenv::create([EASYSWOOLE_ROOT])->load();
        } else {
            throw new Exception(".env不存在,请配置");
        }

        $conf = Config::getInstance();
        $conf->loadFile("App/Helper/functions.php");
        $data = require_once $ConfPath;
        foreach ($data as $key => $val) {
            $conf->setConf((string)$key, (array)$val);
        }
    }

    public static function initRedisPoll()
    {
        $config = new RedisConfig();
        $config->setHost("127.0.0.1");
        $config->setPort(3306);
        try {
            RedisPool::createObject($config);
        } catch (\EasySwoole\Pool\Exception\Exception $e) {
        } catch (RedisPoolException $e) {
            dd($e->getMessage());
        }
    }
}
