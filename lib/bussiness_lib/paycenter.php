<?php
/**
 * Created by PhpStorm.
 * User: xiongkai
 * Date: 2015/6/18
 * Time: 16:33
 */

class TP_Paycenter{


    private static $_instance;

    public static function get_instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * 获取优豆余额
     * @param $ytid
     * @return int
     */
    public function youdou_balance_query($ytid){
        $msg_params = array(
            'version' => '1.0.0',
            'operation' => 'youdouBalanceQuery',
            'accountId' => $ytid,
            'merchantId' => TP_Config::get_paycenter('merchant_id'),
        );
        $msg = urlencode(base64_encode(json_encode($msg_params)));
        $private_key = TP_Config::get_paycenter('private_key');
        $sign = $this->rsa_sign($private_key,$msg);
        $url = TP_Config::get_paycenter('protocol').TP_Config::get_paycenter('host').TP_Config::get_paycenter('youdou_balance_query')."?msg={$msg}&sign=".urlencode($sign);
        $result = json_decode(TP_Curl::get($url));

        $public_key = TP_Config::get_paycenter('public_key');
        $rsa_verify = $this->rsa_verify($public_key,$result->msg,$result->sign);
        if(!$rsa_verify){
            return 0;
        }

        $msg_r = json_decode(base64_decode(urldecode($result->msg)));
        if(is_object($msg_r) && isset($msg_r->errorCode) && $msg_r->errorCode == '0000'){
            return $msg_r->youdouBalance;
        }else{
            return 0;
        }
    }

    /**
     * rsa 签名
     * @param $private_key
     * @param $prestr
     * @return string
     */
    public function rsa_sign($private_key,$prestr) {
        $pkeyid = openssl_get_privatekey($private_key);
        openssl_sign($prestr, $sign, $pkeyid);
        openssl_free_key($pkeyid);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * rsa 验证
     * @param $public_key
     * @param $prestr
     * @param $sign
     * @return bool
     */
    public function rsa_verify($public_key,$prestr, $sign) {
        $sign = base64_decode($sign);
        $pkeyid = openssl_get_publickey($public_key);
        $verify = openssl_verify($prestr, $sign, $pkeyid);
        openssl_free_key($pkeyid);
        return $verify == 1 ? true : false;
    }

}