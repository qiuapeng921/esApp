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
        // 我的群组
        $route->post('user/group', 'Api/User/group');
        // 我的好友
        $route->post('user/friend', 'Api/User/friend');
        // 添加好友
        $route->post('user/addFriend', 'Api/User/addFriend');
        // 审核申请
        $route->post('user/reviewApplication', 'Api/User/reviewApplication');

        // 创建分组
        $route->post('group/create', 'Api/Group/create');
        // 搜索群组
        $route->post('group/search', 'Api/Group/search');
        // 添加群组
        $route->post('group/applyAdd', 'Api/Group/applyAdd');
        // 删除群组
        $route->post('group/delete', 'Api/Group/delete');
    }
}