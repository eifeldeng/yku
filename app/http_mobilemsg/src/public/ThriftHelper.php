<?php
class ThriftHelper {
    private static $_init = false;
    public static function init() {
        if (self::$_init == false) {
            $third_party_dir = YKUTHIRDPARTYPATH . DIRECTORY_SEPARATOR;
            require_once $third_party_dir . 'thriftBase/Thrift/ClassLoader/ThriftClassLoader.php';
            $thirft_protocol_dir = $third_party_dir . "thriftBase/protocol/thrift";
            $loader = new \Thrift\ClassLoader\ThriftClassLoader ();
            $loader->registerNamespace ( 'Thrift', $third_party_dir . "thriftBase" );
            $loader->registerDefinition ( 'xyz', $thirft_protocol_dir );
            
            $loader->register ();
            self::$_init = true;
        }
    }
    public static function get_server_ip(&$config) {
        return is_array ( $config ) ? $config [array_rand ( $config )] : $config;
    }
    public static function get_server_port() {
    }
    public static function load_server_file($server) {
        $third_party_dir = YKUTHIRDPARTYPATH . DIRECTORY_SEPARATOR;
        $thirft_protocol_dir = $third_party_dir . "thriftBase/protocol/thrift";
        require_once $thirft_protocol_dir . $server . ".php";
    }
    public static function write_protocol(&$args, &$transport, &$protocol) {
        $args->write ( $protocol );
        $protocol->writeMessageEnd ();
        $protocol->getTransport ()->flush ();
        $buf = $transport->getBuffer ();
        $len = strlen ( $buf );
        $lenbuf = pack ( 'N', $len );
        $data = $lenbuf . $buf;
        return $data;
    }
}