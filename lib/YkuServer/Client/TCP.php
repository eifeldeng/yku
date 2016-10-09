<?php

// 增加命名空间
namespace YkuServer\Client;

class TCP extends Base {
    public $ip;
    public $port;
    public $data;
    public $key;
    public $timeout = 0.5;
    public $calltime;
    public $receive = false;
    public function __construct($ip, $port, $data, $timeout) {
        $this->ip = $ip;
        $this->port = $port;
        $this->data = $data;
        $this->timeout = $timeout;
    }
    public function setKey($key) {
        $this->key = $key;
    }
    public function getKey() {
        return $this->key;
    }
    public function send(callable $callback) {
        $client = new \Swoole_client ( SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC );
        
        $client->on ( "connect", function ($cli) {
            $cli->send ( $this->data );
        } );
        
        $client->on ( 'close', function ($cli) {
            $this->receive = true;
        } );
        
        $client->on ( 'error', function ($cli) use ($callback) {
            if ($cli instanceof  \swoole_client) {
                $cli->close ();
            }
            call_user_func_array ( $callback, array (
                    'r' => 1,
                    'key' => $this->key,
                    'calltime' => $this->calltime,
                    'error_msg' => 'conncet error' 
            ) );
        } );
        
        $client->on ( "receive", function ($cli, $data) use ($callback) {
            $this->calltime = microtime ( true ) - $this->calltime;
            $cli->close ();
            
            call_user_func_array ( $callback, array (
                    'r' => 0,
                    'key' => $this->key,
                    'calltime' => $this->calltime,
                    'data' => $data 
            ) );
        } );
        
        if ($client->connect ( $this->ip, $this->port, $this->timeout, 1 )) {
            $this->calltime = microtime ( true );
            if (floatval ( ($this->timeout) ) > 0) {
                $this->timer = swoole_timer_after ( floatval ( $this->timeout ) * 1000, function () use ($client, $callback) {
                    if ($this->receive == false) {
                        if ($client instanceof \swoole_client && $client->isConnected ()) {
                            $client->close ();
                        }
                        \SysLog::error ( __METHOD__ . " TIMEOUT ", __CLASS__ );
                        $this->calltime = microtime ( true ) - $this->calltime;
                        call_user_func_array ( $callback, array (
                                'r' => 2,
                                'key' => '',
                                'calltime' => $this->calltime,
                                'error_msg' => 'timeout' 
                        ) );
                    }
                } );
            }
        } else {
            \SysLog::error ( __METHOD__ . " CONNECT FAIL ", __CLASS__ );
            $this->calltime = microtime ( true ) - $this->calltime;
            call_user_func_array ( $callback, array (
                    'r' => 2,
                    'key' => '',
                    'calltime' => $this->calltime,
                    'error_msg' => 'content fail' 
            ) );
        }
    }
}