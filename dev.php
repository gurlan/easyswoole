<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'task_worker_num' => 8,
            'reload_async' => true,
            'task_enable_coroutine' => true,
            'max_wait_time'=>3
        ],
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'PHAR' => [
        'EXCLUDE' => ['.idea', 'Log', 'Temp', 'easyswoole', 'easyswoole.install']
    ],
    'MYSQL'         => [
        'host'                 => '106.12.199.28',
        'port'                 => 3306,
        'user'                 => 'root',
        'password'             => 'qi&YyPatY2D7',
        'database'             => 'easyswoole',
        'timeout'              => 30,
        'charset'              => 'utf8mb4',
        'connect_timeout'      => '5',//连接超时时间
    ],
    'OSS' =>[
        'key' =>'LTAIv27muzQBHGxS',
        'secret' =>'yPgLNaQFppm2DM4dbZklJjjoOpv7DK',
        'bucket'=>'test20181017'
    ],
    'WEIXIN' =>[
        'appid'=>'wxcecf234ee62295c6',
        'secret'=>'790b4f6b77e612a4a4e28da89eb0dd80'
    ]

];
