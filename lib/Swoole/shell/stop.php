<?php
/**
 * Created by PhpStorm.
 * User: yuanyizhi
 * Date: 15/6/19
 * Time: 下午11:57
 */
#!/usr/local/php/bin/php -q


//读取配置，启动对应的server 根据传进来的server名字 已经知道协议类型


// 定义根目录
define('SWOOLEBASEPATH', dirname(dirname(__FILE__)));
// 载入Swoole 框架
require_once SWOOLEBASEPATH . '/require.php';
//读取配置
$cmd = $argv[1];   //cmd name
$name = $argv[2];
