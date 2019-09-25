<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Exception\ExceptionHandler;
use App\Process\HotReload;
use App\Utility\Blade;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use App\WebSocket\SocketParser;
use EasySwoole\Component\Di;
use EasySwoole\Http\Message\Status;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\MysqliPool\MysqlPoolException;
use EasySwoole\Socket\Dispatcher;
use EasySwoole\Template\Render;
use Throwable;

use EasySwoole\EasySwoole\Config as ESConfig;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\Mysqli\Config as MysqlConfig;

class EasySwooleEvent implements Event
{
    /**
     * @throws MysqlPoolException
     */
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 载入项目 Conf 文件夹中所有的配置文件
        self::loadConf(EASYSWOOLE_ROOT . '/config.php');
        // 异常捕捉
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class, 'handle']);

        /**
         * 初始化mysql
         */
        $configData = ESConfig::getInstance()->getConf('MYSQL');
        $config = new MysqlConfig($configData);
        Mysql::getInstance()->register('default', $config);
        $devConfigData = ESConfig::getInstance()->getConf('MYSQL-SlAVE');
        $devConfig = new MysqlConfig($devConfigData);
        Mysql::getInstance()->register('test', $devConfig);
    }

    /**
     * @param EventRegister $register
     * @throws \Exception
     */
    public static function mainServerCreate(EventRegister $register)
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());

        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new SocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  on message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage, function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });

        // TODO mysql redis 预加载
        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
            if ($server->taskworker == false) {
                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(ESConfig::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
                PoolManager::getInstance()->getPool(RedisPool::class)->preLoad(ESConfig::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
            }
        });
        // TODO 模板
        Render::getInstance()->getConfig()->setRender(new Blade());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());
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
        $allow_origin = ESConfig::getInstance()->getConf('ORIGIN');
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
     * 加载自定义配置
     * @param $ConfPath
     */
    public static function loadConf($ConfPath)
    {
        $conf = ESConfig::getInstance();
        $data = require_once $ConfPath;
        foreach ($data as $key => $val) {
            $conf->setConf((string)$key, (array)$val);
        }
    }
}