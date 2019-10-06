<?php
/**
 * mysql配置
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 13:25
 */

return [
    "MYSQL" => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'test'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', '123456'),
        'timeout' => env('DB_TIME', 5),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'POOL_MAX_NUM' => env('DB_POOL_MAX_NUM', '50'),
        'POOL_MIN_NUM' => env('DB_POOL_MIN_NUM', 1),
        'POOL_TIME_OUT' => env('DB_POOL_TIME_OUT', 1),
    ],
    "REDIS" => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', '6379'),
        'auth' => env('REDIS_PASSWORD', ''),
        'POOL_MAX_NUM' => env('REDIS_POOL_MAX_NUM', '20'),
        'POOL_MIN_NUM' => env('REDIS_POOL_MIN_NUM', 1),
        'POOL_TIME_OUT' => env('REDIS_POOL_TIME_OUT', 1),
    ],
    'ORIGIN' => [
        'http://localhost:8080',
        'http://bar.rd029.com',
    ],
];
