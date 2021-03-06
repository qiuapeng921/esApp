<?php

return [
    'SERVER_NAME' => env('APP_NAME', 'esServer'),
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER,
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => swoole_cpu_num(),//运行的  worker进程数量
            'max_request' => 5000,// worker 完成该数量的请求后将退出，防止内存溢出
            'task_max_request' => 1000,// task_worker 完成该数量的请求后将退出，防止内存溢出
            'reload_async' => true,
            'document_root' => EASYSWOOLE_ROOT . '/public',  // 静态资源目录
            'enable_static_handler' => true,
        ],
        'TASK' => [
            'workerNum' => swoole_cpu_num(),//运行的 task_worker 进程数量
            'maxRunningNum' => 128,
            'timeout' => 15
        ],
    ],
    'TEMP_DIR' => EASYSWOOLE_ROOT . '/storage/temp',
    'LOG_DIR' => EASYSWOOLE_ROOT . '/storage/log',
    'CONSOLE' => [
        'ENABLE' => true,
        'LISTEN_ADDRESS' => '127.0.0.1',
        'PORT' => 9500,
        'USER' => 'root',
        'PASSWORD' => '123456'
    ],
    'FAST_CACHE' => [
        'PROCESS_NUM' => 0,
        'BACKLOG' => 256,
    ],
    'DISPLAY_ERROR' => env('APP_DEBUG', false),
    'APP_HOST' => env('APP_URL', '127.0.0.1')
];
