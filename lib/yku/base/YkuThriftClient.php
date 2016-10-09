<?php
class YkuThriftClient {
    protected $transport;
    protected $client;
    protected $seqid = 0;
    protected $ip;
    protected $port;
    protected $time_out;
    protected $write_transport;
    protected $write_protocol;
    protected $read_transport;
    protected $read_protocol;
    public function __construct() {
        ThriftHelper::init ();
    }
    protected function init_thrift($server_name, $server_file) {
        // 获取服务器配置
        $config = Config::loadConfig ( $server_name );
        
        $this->ip = ThriftHelper::get_server_ip ( $config ['server_ips'] );
        if ($this->port == null) {
            $this->port = $config ['server_port'];
        }
        if ($this->time_out == null) {
            $this->time_out = $config ['server_recv_timeout'] / 1000;
        }
        // 加载文件
        ThriftHelper::load_server_file ( $server_file );
        // 初始化
        $this->write_transport = new \Thrift\Transport\TMemoryBuffer ();
        $this->write_protocol = new \Thrift\Protocol\TBinaryProtocol ( $this->write_transport );
    }
    protected function read_data($result, $value) {
        $rseqid = 0;
        $fname = null;
        $mtype = 0;
        $head = "";
        $transport = new \Thrift\Transport\TMemoryBuffer ( $value );
        $protocol = new \Thrift\Protocol\TBinaryProtocolAccelerated ( $transport );
        $protocol->readI32 ( $head );
        $protocol->readMessageBegin ( $fname, $mtype, $rseqid );
        $result->read ( $protocol );
        $protocol->readMessageEnd ();
        return $result->success;
    }
    protected function reset_protocol() {
        $this->write_transport = new \Thrift\Transport\TMemoryBuffer ();
        $this->write_protocol = new \Thrift\Protocol\TBinaryProtocol ( $this->write_transport );
    }
}