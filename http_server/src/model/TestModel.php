<?php
class TestModel {
    public function test() {
        yield array (
                'r' => 0,
                'data' => 'yield ' 
        );
    }
    public function udpTest() {
        yield new Swoole\Client\UDP ( $ip, $port, $data, $timeout );
    }
    public function httpTest() {
        $url = 'http://www.qq.com';
        $httpRequest = new Swoole\Client\HTTP ( $url );
        $data = 'testdata';
        $header = array (
                'Content-Length' => 12345 
        );
        yield $httpRequest->get ( $url );
        // yield $httpRequest->post($path, $data, $header);
    }
    public function muticallTest() {
        $ip = '127.0.0.1';
        $data = 'test';
        $timeout = 0.5; // second
        
        $calls = new Swoole\Client\Multi ();
        
        $firstReq = new Swoole\Client\TCP ( $ip, '9905', $data, $timeout );
        $secondReq = new Swoole\Client\UDP ( $ip, '9904', $data, $timeout );
        $calls->request ( $firstReq, 'first' ); // first request
        $calls->request ( $secondReq, 'second' ); // second request
        
        yield $calls;
    }
    public function HttpmuticallTest() {
        $calls = new Swoole\Client\Multi ();
        $qq = new Swoole\Client\HTTP ( "http://www.qq.com/" );
        $calls->request ( $qq, "qq" );
        
        yield $calls;
    }
    public function tcpTest() {
        $ip = '127.0.0.1';
        $port = '9905';
        $data = 'test';
        $timeout = 0.5; // second
        yield new Swoole\Client\TCP ( $ip, $port, $data, $timeout );
    }
    public function mysqlTest() {
        $sql = new Swoole\Client\MYSQL ( array (
                'host' => '127.0.0.1',
                'port' => 3345,
                'user' => 'root',
                'password' => 'root',
                'database' => 'test',
                'charset' => 'utf-8' 
        ) );
        $ret = (yield $sql->query ( 'show tables' ));
        var_dump ( $ret );
        $ret = (yield $sql->query ( 'desc test' ));
        var_dump ( $ret );
    }
}