<?php

namespace YkuServer\Network;

class HttpServer extends \YkuServer\Network\TcpServer {
    public function init() {
        $this->enableHttp = true;
    }
}