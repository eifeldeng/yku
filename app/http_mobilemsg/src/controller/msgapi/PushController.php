<?php
use YkuServer\Coroutine\SysCall;
class PushController extends Controller {
    const DEFAULT_EXPIRE_TIME =259200;
    public function action_get_innerpage_badge_and_point() {
        $response = $this->argv ['response'];
        $uid = $this->request_data ( 'uid', "" );
        $pf = $this->request_data ( 'pf', 1 );
        $device_id = $this->request_data ( 'device_id', 1 );
        $last_msg_create_time = $this->request_data ( 'last_msg_create_time', 0 );
        $access_token = $this->request_data ( "access_token" );
        $sig = $this->request_data ( 'sig' );
        $appid = $this->request_data ( 'appid' );
        $ts = $this->request_data ( 'ts' );
        $now_time = time ();
        $user_type = 0;
        
        
        $data = array (
                'notify_type' => 2,
                'red_point' => 0,
                'badge_num' => 0,
                'device_msg_point' => 0,
                'last_msg_create_time' => ( int ) $last_msg_create_time 
        );
        
        $user_msg_model = new UserMsgModel ();
        if ($uid) {
            $badge_yku_client = new BadgeYkuClient ();
            $badge_yku_client->init ();
            
            $result = (yield $badge_yku_client->get_badge_number ( $uid, $device_id, $op_term, $user_type ));
            if (isset ( $result->ret ) && $result->ret == 0) {
                $data ['badge_num'] = $result->badgenumber;
            }
            if ($data ['badge_num'] > 0) {
                $data ['notify_type'] = 1;
                $data ['red_point'] = 1;
            }
            if ($data ['red_point'] == 0) {
                $data ['red_point'] = yield $user_msg_model->get_user_red_point ( $uid, $plat, $op_term, $user_type );
            }
            SysLog::debug ( "get_user_red_point", $data ['red_point'] );
            $data ['last_msg_create_time'] = $now_time;
        } else {
            // 计算未登录用户的红点和挂数
            $user_msg_model->get_unlogin_user_badgenum_and_point ( $plat, $last_msg_create_time, $now_time, $data ['red_point'], $data ['badge_num'] );
            if ($data ['badge_num'] > 0) {
                $data ['notify_type'] = 1;
            } else {
                $data ['notify_type'] = 2;
            }
        }
        
        /**
         * 设备红点消息
         */
        \SysLog::debug ( "red point data", $data );
      
        $this->render ( $data );
        yield SysCall::end ( "action_get_innerpage_badge_and_point end" );
    }
   
}
