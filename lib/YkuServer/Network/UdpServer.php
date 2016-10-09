<?php
namespace YkuServer\Network;
/**
 * Class Server
 * @package YkuServer\Network
 */
class UdpServer extends \YkuServer\Server
{
    protected $sockType = SWOOLE_SOCK_UDP;

    public $setting = array( // udp server 默认配置

    );
}
