<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2015/7/15
 * Time: 14:21
 */

class TP_Stat{

    private static $_instance;

    public static function get_instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 支付来源列表接口
     * @param $level
     * @param int $source_id
     * @return bool
     */
    public function source($level,$source_id=0){
        $params = array(
            'level' => $level,
            'source_id' => $source_id,
        );
        return $this->request('source',$params);
    }

    /**
     * 订单统计接口
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function order($date,$dimensions){
        $params = array(
            'date' => $date,
            'dimensions' => $dimensions,
        );
        return $this->request('order',$params);
    }

    /**
     * 转化监控接口
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function stream($date,$dimensions){
        $params = array(
            'date' => $date,
            'dimensions' => $dimensions,
        );
        return $this->request('stream',$params);
    }

    /**
     * 订单转化接口
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function order_stream($date,$dimensions){
        $params = array(
            'date' => $date,
            'dimensions' => $dimensions,
        );
        return $this->request('order_stream',$params);
    }

    /**
     * 用户统计接口
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function user($date,$dimensions){
        $params = array(
            'date' => $date,
            'dimensions' => $dimensions,
        );
        return $this->request('user',$params);
    }

    /**
     * 会员有效用户数统计接口
     * @param $date
     * @return bool
     */
    public function member($date){
        $params = array(
            'date' => $date,
        );
        return $this->request('member',$params);
    }

    /**
     * 会员留存用户数统计接口
     * @param $date
     * @return bool
     */
    public function member_retain($date){
        $params = array(
            'date' => $date,
        );
        return $this->request('member_retain',$params);
    }

    /**
     * 开通会员观看视频统计接口
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function member_view($date,$dimensions){
        $params = array(
            'date' => $date,
            'dimensions' => $dimensions,
        );
        return $this->request('member_view',$params);
    }

    /**
     * 全站用户观看行为分析大数据统计
     * @param $start
     * @param $end
     * @param int $member_type
     * @param int $platform
     * @param int $content_type
     * @return bool
     */
    public function user_view($start,$end,$member_type = 1,$platform = 1,$content_type = 1){
        $params = array(
            'start' => $start,
            'end' => $end,
            'member_type' => $member_type,
            'platform' => $platform,
            'content_type' => $content_type,
        );
        return $this->request('user_view',$params);
    }

    /**
     * 按视频未读 用户观看行为分析大数据统计
     * @param $start
     * @param $end
     * @param int $platform
     * @param $video_id
     * @return bool
     */
    public function video_show_view_by_video_id($start,$end,$platform = 1,$video_id){
        $params = array(
            'start' => $start,
            'end' => $end,
            'platform' => $platform,
            'video_id' => $video_id,
        );
        return $this->request('video_show_view',$params);
    }

    /**
     * 按节目未读 用户观看行为分析大数据统计
     * @param $start
     * @param $end
     * @param int $platform
     * @param $show_id
     * @return bool
     */
    public function video_show_view_by_show_id($start,$end,$platform = 1,$show_id){
        $params = array(
            'start' => $start,
            'end' => $end,
            'platform' => $platform,
            'show_id' => $show_id,
        );
        return $this->request('video_show_view',$params);
    }

    /**
     * 数据统计相关接口请求
     * @param $api_name
     * @param $date
     * @param $dimensions
     * @return bool
     */
    public function request($api_name,$params){
        $url = "http://".TP_Helper::array_random_element(TP_Config::get_stat('ip'))."/statis/".$api_name.".json";
        $pub_key = TP_Config::get_stat('pub_key');
        $sign_type = TP_Config::get_stat('sign_type');
        $secret_key = TP_Config::get_stat('secret_key');
        $params['pub_key'] = $pub_key;
        $params['sign_type'] = $sign_type;
        $params['sign'] = $this->create_sign($params,$secret_key,$sign_type);
        $header = array("Host: ".TP_Config::get_stat('host'));
        $request_timeout = TP_Config::get_stat('request_timeout');
        $result = TP_Curl::post($url,$params,$header,$request_timeout);
        //echo $result;
        $ret = json_decode($result,true);
        if(isset($ret['error']) && $ret['error'] == 1){
            return $ret['result'];
        }else{
            return false;
        }
    }

    /**
     * 生成签名
     */
    public function create_sign(array $params, $secretKey, $algo="md5") {
        ksort($params);
        reset($params);
        $signPars = '';
        while(list($k, $v) = each($params)){
            if('' === $v) continue;
            $signPars .= $k . '=' . $v . '&';
        }
        $signPars = substr($signPars, 0, strlen($signPars)-1);
        $sign = strtolower(hash_hmac($algo, $signPars, $secretKey));
        return $sign;
    }
}