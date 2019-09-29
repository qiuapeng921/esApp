<?php

namespace App\HttpController;

use App\Router\ApiRouter;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

/**
 * Class Router
 * @package App\HttpController
 */
class Router extends AbstractRouter
{
    /**
     * 初始化
     * @param RouteCollector $route
     */
    public function initialize(RouteCollector $route)
    {
        $route->get('/', '/Index/index');
        $route->get('/hall', '/Index/hall');

        $route->get('/login', 'Home/User/login');
        $route->get('/register', 'Home/User/register');

        $route->get('/friend', 'Home/User/friend');
        $route->get('/addFriend', 'Home/User/addFriend');
        $route->get('/message', 'Home/User/message');

        $route->get('/group', 'Home/User/group');
        $route->get('/groupMessage', 'Home/User/groupMessage');

        $route->addGroup('/api/', function (RouteCollector $route) {
            (new ApiRouter())->setRouter($route);
        });

        // 开启全局路由(只有定义的地址才可以访问)
        $this->setGlobalMode(true);
        // 空方法
        $this->setMethodNotAllowCallBack(function (Request $request, Response $response) {
            $response->withHeader('Content-type', 'text/html;charset=UTF-8');
            $response->write('未找到处理方法');
            $response->end();
        });
        // 空路由
        $this->setRouterNotFoundCallBack(function (Request $request, Response $response) {
            $response->withHeader('Content-type', 'text/html;charset=UTF-8');
            $response->write('未找到路由匹配');
            $response->end();
        });
    }
}
