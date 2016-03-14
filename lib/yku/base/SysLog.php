<?php
class SysLog {
    
    // 默认全开
    public static $logLevel = array (
            'debug' => 'debug',
            'info' => 'info',
            'notice' => 'notice',
            'warning' => 'warning',
            'error' => 'error',
            'fatal' => 'fatal' 
    );
    public static $defaultPermission = 0777;
    
    // 默认log目录
    public static $logDir = '/tmp/log';
    public static $enable_task_log = false;
    /**
     * 格式化日志消息
     *
     * @param unknown $level            
     * @param unknown $logName            
     * @param unknown $message            
     */
    private static function formatMessage($level, $logName, $message) {
        $level = strtoupper ( $level );
        $time = date ( 'Y-m-d H:i:s' );
        $pid = function_exists ( 'posix_getpid' ) ? posix_getpid () : 0;
        $message = print_r ( $message, true );
        return "[{$pid}] [{$time}] [{$level}] [{$logName}] : {$message}" . PHP_EOL;
    }
    
    /**
     * 写入日志
     *
     * @param unknown $logName            
     * @param unknown $message            
     * @param unknown $logLevel            
     * @return boolean
     */
    private static function log($logName, $message, $logLevel) {
        if (empty ( self::$logDir )) {
            // 初始化，如果失败，返回
            if (! self::init ()) {
                return false;
            }
        }
        
        if (empty ( self::$logLevel ) || ! in_array ( $logLevel, self::$logLevel )) {
            // 不在log等级内，返回
            return false;
        }
        
        if (! isset ( $logName )) {
            // 没有设置log名，返回
            return false;
        }
        
        $dir = self::$logDir;
        $dirArr = explode ( '/', trim ( $dir ) );
        $logFileName = "yku_" . date ( "Y-m-d" );
        $tmpDir = '';
        $faDir = '';
        
        for($i = 1; $i < count ( $dirArr ); $i ++) {
            $faDir .= '/' . $dirArr [$i - 1];
            $tmpDir .= '/' . $dirArr [$i];
            // 判断目录是否存在
            if (! is_dir ( $tmpDir )) {
                // 判断目录是否可写
                if (! is_writeable ( $faDir )) {
                    // 不可写
                    return false;
                }
                // 创建目录
                $res = mkdir ( $tmpDir, self::$defaultPermission, true );
                if (! $res) {
                    // 创建失败
                    return false;
                }
            }
        }
        error_log ( self::formatMessage ( $logLevel, $logName, $message ), 3, $dir . '/' . $logFileName . '.log' );
        return true;
    }
    public static function notice($logName, $message) {
        return self::log ( $logName, $message, 'notice' );
    }
    public static function info($logName, $message) {
        return self::log ( $logName, $message, 'info' );
    }
    public static function warning($logName, $message) {
        return self::log ( $logName, $message, 'warning' );
    }
    public static function error($logName, $message) {
        return self::log ( $logName, $message, 'error' );
    }
    public static function debug($logName, $message) {
        return self::log ( $logName, $message, 'debug' );
    }
    public static function fatal($logName, $message) {
        return self::log ( $logName, $message, 'fatal' );
    }
    
    /**
     * [init 初始化log配置] 有默认配置
     *
     * @return [type] [description]
     */
    public static function init($logInfo = null) {
        date_default_timezone_set ( 'PRC' );
        
        if (empty ( $logInfo )) {
            return true;
        }
        self::$logDir = isset ( $logInfo ['log_path'] ) ? $logInfo ['log_path'] : self::$logDir;
        self::$logLevel = array ();
        foreach ( $logInfo ['log_level'] as $key => $value ) {
            if ($value) {
                self::$logLevel [$key] = $key;
            }
        }
        return true;
    }
}
