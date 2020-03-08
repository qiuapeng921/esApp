<?php
/**
 * Created by PhpStorm.
 * User: qap
 * Date: 2019/9/27
 * Time: 10:27
 */

namespace App\Router;

use FastRoute\RouteCollector;

class ApiRouter
{
    public function setRouter(RouteCollector $route)
    {
        // 登陆
        $route->post('auth/login', 'Api/Auth/login');
        // 注册
        $route->post('auth/register', 'Api/Auth/register');
        // 退出
        $route->post('auth/logout', 'Api/Auth/register');

        // 用户信息
        $route->post('user/info', 'Api/User/info');
    }
}