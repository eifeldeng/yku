<?php
namespace YkuServer\Network;
/**
 * Class Server
 * @package YkuServer\Network
 */
class TcpServer extends \YkuServer\Server implements \YkuServer\Server\Driver
{
    protected $sockType = SWOOLE_SOCK_TCP;

    public $setting = array(
        //      'open_cpu_affinity' => 1,
        'open_tcp_nodelay' => 1,
    );
}
