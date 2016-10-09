<?php
define ( 'YKUBASEPATH', dirname ( dirname ( __FILE__ ) ) );
require_once 'AutoLoad.php';
// 添加swoole的代码
require_once YKUBASEPATH . '/YkuServer/require.php';
// autoload yku所有的代码
AutoLoad::addRoot ( YKUBASEPATH . '/yku' );
class Yku {
    // config用来配置各个字段 如 url log 等
    public static function createHttpApplication($config) {
        AutoLoad::addRoot ( YKUTHIRDPARTYPATH );
        AutoLoad::addRoot ( dirname ( dirname ( $config ) ) );
        SysLog::init ( UserConfig::getConfig ( 'log' ) );
        return new YKUHttpServ ();
    }
    public static function createUdpApplication($config) {
    }
    public static function createTcpApplication() {
    }
    public static function createWebSocketApplication() {
    }
}
