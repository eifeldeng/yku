<?php

namespace db;

class RedisSlice {
    protected $node_config = array ();
    private $redis;
    const REDIS_TIMEOUT = 0.5;
    const REDIS_RETRY_INTERVAL = 10;
    const REDIS_PERSISTENT = false;
    public function __construct($node_config) {
        $this->node_config = $node_config;
    }
    public function __call($func, array $args) {
        $result = $this->proxy ( $func, $args );
        return $result;
    }
    protected function proxy($func, array $args) {
        if (1 > count ( $args )) {
            \syslog::fatal ( 'Redis', array (
                    'class' => 'RedisSlice',
                    'method' => 'proxy',
                    'func' => $func,
                    'args' => $args,
                    'error' => 'miss key' 
            ) );
        }
        $redis = $this->redis;
        try {
            $result = call_user_func_array ( array (
                    $redis,
                    $func 
            ), $args );
            return $result;
        } catch ( \Exception $e ) {
            try {
                $redis->ping ();
                \SysLog::error ( "REDIS", 'Redis paramter error ' );
                return null;
            } catch ( \Exception $e ) {
                \SysLog::error ( "REDIS", 'Redis try to reconnect ' );
                $connect_result = $this->connect ();
                if ($connect_result != false) {
                    $result = call_user_func_array ( array (
                            $redis,
                            $func 
                    ), $args );
                    return $result;
                }
                \SysLog::error ( "REDIS", 'Redis reconnect fail' );
            }
        }
    }
    public function connect() {
        if (! isset ( $this->node_config ['ip'], $this->node_config ['port'] )) {
            \SysLog::error ( 'REDIS', 'Redis  setting error' );
            return false;
        }
        
        $host = trim ( $this->node_config ['ip'] );
        $port = intval ( $this->node_config ['port'] );
        
        if (isset ( $this->node_config ['disabled'] ) && $this->node_config ['disabled']) {
            \SysLog::error ( 'REDIS', 'Redis is down for maintenance!' );
            return false;
        }
        
        $timeout = self::REDIS_TIMEOUT;
        
        if (isset ( $node_config ['timeout'] )) {
            $timeout = intval ( $node_config ['timeout'] );
            
            if ($timeout < 0) {
                $timeout = self::REDIS_TIMEOUT;
            }
        }
        $redis = new \Redis ();
        $connection = FALSE;
        
        if (self::REDIS_PERSISTENT) {
            $connection = $redis->pconnect ( $host, $port, $timeout, $connectKey, self::REDIS_RETRY_INTERVAL );
        } else {
            $connection = $redis->connect ( $host, $port, $timeout, NULL, self::REDIS_RETRY_INTERVAL );
        }
        if (! $connection) {
            \SysLog::error ( 'REDIS', 'Connect to redis timeout' );
            return false;
        }
        
        if (isset ( $node_config ['password'] ) && ($passwd = $node_config ['password']) && ! $redis->auth ( $passwd )) {
            \SysLog::error ( 'REDIS', 'Redis Auth fail' );
            return false;
        }
        $this->redis = $redis;
        return $this->redis;
    }
}

