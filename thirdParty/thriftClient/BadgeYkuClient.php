<?php
class BadgeYkuClient extends YkuThriftClient {
    public function init() {
        // 加载文件
        $server_name = "badge_server";
        $server_file = "/xyz/badgemsgsvr/badgemsgsvr";
        $this->init_thrift ( $server_name, $server_file );
        // 初始化
    }
    public function get_badge_number($uid, $device_id, $op_term, $user_type) {
        $this->reset_protocol();
        $termproperty = new \xyz\badgemsgsvr\TerminalProperty ();
        $termproperty->device_id = $device_id;
        $termproperty->uid = $uid;
        $termproperty->op_term = $op_term;
        $termproperty->user_type = $user_type;
        
        $this->write_protocol->writeMessageBegin ( 'count_badgenumber', \Thrift\Type\TMessageType::CALL, $this->seqid );
        
        $args = new \xyz\badgemsgsvr\badgemsgsvr_count_badgenumber_args ();
        $args->termproperty = $termproperty;
        $data = ThriftHelper::write_protocol ( $args, $this->write_transport, $this->write_protocol );
        
        $res = (yield new YkuServer\Client\TCP ( $this->ip, $this->port, $data, $this->time_out ));
        
        if ($res ['r'] == 0) {
            $value = $res ['data'];
            $result = new \xyz\badgemsgsvr\badgemsgsvr_count_badgenumber_result ();
            yield ($this->read_data ( $result, $value ));
        } else {
            SysLog::error ( __METHOD__ . " yield failed res == " . print_r ( $res, true ), __CLASS__ );
            yield array (
                    'r' => 1,
                    'error_msg' => 'yield failed' 
            );
        }
    }
}