<?php

// !/usr/local/php/bin/php -q

// 读取配置，启动对应的server 根据传进来的server名字 已经知道协议类型

// 定义根目录
define ( 'SWOOLEBASEPATH', dirname ( dirname ( __FILE__ ) ) );
// 载入Swoole 框架
require_once SWOOLEBASEPATH . '/require.php';
// 读取配置
$cmd = $argv [1]; // cmd name
$name = $argv [2];

// 需要cmd 和 name name 支持 all 和 具体的serverName
if (! $cmd || ! $name) {
    echo "please input cmd and server name: start all,start testserv ";
    exit ();
}
// 读取配置文件 然后启动对应的server
$configPath = (dirname ( dirname ( SWOOLEBASEPATH ) )) . '/conf/' . $name . '.ini';
define ( 'SERVER_NAME', $name );
define ( 'SERVER_INI', $configPath );

if (! file_exists ( $configPath )) {
    throw new \Exception ( "[error] profiles [$configPath] can not be loaded" );
}
// Load the configuration file into an array
$config = parse_ini_file ( $configPath, true );
// 根据config里面的不同内容启动不同的server 定义网络层 UDP、TCP
if ($config ['server'] ['type'] == 'http') { //
    $server = new \Swoole\Network\HttpServer ();
    $server->init ();
} elseif ($config ['server'] ['type'] == 'tcp') {
    $server = new \Swoole\Network\TcpServer ();
} elseif ($config ['server'] ['type'] == 'udp') {
    $server = new \Swoole\Network\UdpServer ();
}
// 配置文件
$server->setConfig ( $config );
// 设置线程名称
$server->setProcessName ( $name );
// yku框架启动文件
$server->setRequire ( $config ['server'] ['root'] );
// 启动server
$server->run ();