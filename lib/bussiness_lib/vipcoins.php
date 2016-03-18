<?php
/**
 * Created by PhpStorm.
 * User: xiongkai
 * Date: 2015/6/23
 * Time: 15:18
 */

class TP_Vipcoins{

    private static $_instance;

    public static function get_instance(){
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 积分账户查询
     */
    public function get_account_by_id($uid){
        $platform = TP_Config::get_vipcoins('platform');
        $merchant_id = TP_Config::get_vipcoins('merchant_id');
        $pub_key = TP_Config::get_vipcoins('pub_key');
        $sign_type = TP_Config::get_vipcoins('sign_type');
        $param = array(
            'version' => '1.0',
            'apitime' => time(),
            'clentip' => TP_Helper::get_ip(),
            'uid' => $uid,
            'platform' => $platform,
            'merchant_id' => $merchant_id,
            'pub_key' => $pub_key,
            'sign_type' => $sign_type,
        );
        $secretKey = TP_Config::get_vipcoins('secret_key');
        $param['sign'] = $this->create_sign($param,$secretKey,$sign_type);

        $vipcoins_ip = TP_Helper::array_random_element(TP_Config::get_vipcoins('ip'));
        $request_url = "http://".$vipcoins_ip.'/vipcoins/get_account_by_id.json?'.http_build_query($param);
        $host = TP_Config::get_vipcoins('host');
        $header = array("Host: {$host}");

        $request_timeout = TP_Config::get_vipcoins('request_timeout');
        $result = TP_Curl::get($request_url,$header,$request_timeout);
        if(!$result) return 0;
        $ret = json_decode($result);
        if(isset($ret->error) && $ret->error == 1){
            return isset($ret->result->coins) ? $ret->result->coins : 0;
        }
        TP_Log::error("get_account_by_id|url:{$request_url}|result:{$result}");
        return 0;
    }

    /**
     *  积分转账
     * @param $uid
     * @param $coins
     * @param $order
     * @return int
     */
    public function coin_transfer($uid,$coins,$order){
        $platform = TP_Config::get_vipcoins('platform');
        $merchant_id = TP_Config::get_vipcoins('merchant_id');
        $pub_key = TP_Config::get_vipcoins('pub_key');
        $sign_type = TP_Config::get_vipcoins('sign_type');
        $param = array(
            'version' => '1.0',
            'apitime' => time(),
            'clientip' => TP_Helper::get_ip(),
            'type' => 1,
            'from_uid' => $merchant_id,
            'to_uid' => $uid,
            'to_platform' => $platform,
            'coins' => $coins,
            'out_order' => $order,
            'desc' => '签到送积分',
            'pub_key' => $pub_key,
            'sign_type' => $sign_type,
        );
        $secretKey = TP_Config::get_vipcoins('secret_key');
        $param['sign'] = $this->create_sign($param,$secretKey,$sign_type);

        $vipcoins_ip = TP_Helper::array_random_element(TP_Config::get_vipcoins('ip'));
        $request_url = "http://".$vipcoins_ip.'/vipcoins/coin_transfer.json?'.http_build_query($param);
        $host = TP_Config::get_vipcoins('host');
        $header = array("Host: {$host}");

        $request_timeout = TP_Config::get_vipcoins('request_timeout');
        $result = TP_Curl::get($request_url,$header,$request_timeout);
        if(!$result) return false;
        $ret = json_decode($result);
        if(isset($ret->error) && $ret->error == 1){
            return true;
        }
        TP_Log::error("get_account_by_id|url:{$request_url}|result:{$result}");
        return false;
    }

    /**
     * 积分消费
     * @param $uid
     * @param $coins
     * @param $out_order
     * @return bool
     */
    public function coin_consume($uid,$coins,$out_order){
        $platform = TP_Config::get_vipcoins('platform');
        $merchant_id = TP_Config::get_vipcoins('consume_merchant_id');
        $pub_key = TP_Config::get_vipcoins('consume_pub_key');
        $sign_type = TP_Config::get_vipcoins('sign_type');
        $param = array(
            'version' => '1.0',
            'apitime' => time(),
            'clientip' => TP_Helper::get_ip(),
            'merchantid' => $merchant_id,
            'uid' => $uid,
            'platform' => $platform,
            'coins' => $coins,
            'out_order' => $out_order,
            'desc' => '签到消费积分',
            'pub_key' => $pub_key,
            'sign_type' => $sign_type,
        );
        $secretKey = TP_Config::get_vipcoins('consume_secret_key');
        $param['sign'] = $this->create_sign($param,$secretKey,$sign_type);

        $vipcoins_ip = TP_Helper::array_random_element(TP_Config::get_vipcoins('ip'));
        $request_url = "http://".$vipcoins_ip.'/vipcoins/coin_consume.json?'.http_build_query($param);
        $host = TP_Config::get_vipcoins('host');
        $header = array("Host: {$host}");

        $request_timeout = TP_Config::get_vipcoins('request_timeout');
        $result = TP_Curl::get($request_url,$header,$request_timeout);
        TP_Log::error("coin_consume|url:{$request_url}|result:{$result}");
        if(!$result) return false;
        $ret = json_decode($result);
        return $ret;
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

        // 去掉最后一个 "&"
        $signPars = substr($signPars, 0, strlen($signPars)-1);

        $sign = strtolower(hash_hmac($algo, $signPars, $secretKey));

        return $sign;
    }

}