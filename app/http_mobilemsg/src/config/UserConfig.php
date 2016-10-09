<?php

// 用来配置用户自定义的路由规则 以及一些log级别等
class UserConfig {
    public static $Conf = array (
            
            'log' => array (
                    'log_path' => '/opt/logs/http_mobilemsg',
                    'log_level' => array (
                            'info' => 0,
                            'warning' => 1,
                            'notice' => 1,
                            'error' => 1,
                            'debug' => 0,
                            'fatal' => 1 
                    ) 
            ),
            
            'router' => array (
                    'info' => 1,
                    'error' => 1 
            ),
            'preFilter' => array (
                    'info' => 1,
                    'error' => 1 
            ),
            'postFilter' => array (
                    'info' => 1,
                    'error' => 1 
            ),
            
    );
    public static function getConfig($val = null) {
        if ($val == null) {
            return self::$Conf;
        }
        return self::$Conf [$val];
    }
}
?>