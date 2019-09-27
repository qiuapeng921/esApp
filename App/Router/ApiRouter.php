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
        $route->post('auth/login', 'Api/Auth/login');
        $route->post('auth/register', 'Api/Auth/register');
        $route->post('auth/logout', 'Api/Auth/register');
    }
}