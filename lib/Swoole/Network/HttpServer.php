<?php

namespace Swoole\Network;

class HttpServer extends \Swoole\Network\TcpServer {
    public function init() {
        $this->enableHttp = true;
    }
}