<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2015/9/8
 * Time: 15:24
 */

class TP_Sms{


    private static $_instance;

    public static function get_instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * 发送短信
     * @param $posts
     * @param $content
     * @return bool
     */
    public function message_send_new($posts,$content){
        $url = "http://".TP_Helper::array_random_element(TP_Config::get_sms('host'))."/sms/message/send_new.json";
        $token = TP_Config::get_sms('token');
        $template_name = TP_Config::get_sms('template_name');
        $rule_name = TP_Config::get_sms('rule_name');
        $tcode = TP_Config::get_sms('tcode');
        $params = array(
            'token' => $token,//项目证书
            'posts' => $posts,//收信人列表，最多支持100个号码
            'content' => $content,//短信内容，最多支持60字
            'sms_class' => 1,//短信类别，1验证码2通知3市场
            'sms_type' => 0,//短信类型，0文本1语音，默认为0，暂时只有海外验证短信支持语音
            'region' => 'CN',//如是发送给国外手机号，需填写手机号归属地国家，默认中国CN
            'template_name' => $template_name,//短信模板名称
            'rule_name' => $rule_name,//验证码生成规则名称
            'ip' => TP_Helper::get_ip(),
            'tcode' => $tcode,//业务代码描述，验证码短信必传
        );
        $header = array();
        $request_timeout = TP_Config::get_sms('request_timeout');
        $result = TP_Curl::post($url,$params,$header,$request_timeout);
        TP_Log::sms("message_send_new|url:".$url."|params:".http_build_query($params)."|ret:".$result);
        if(!$result) return false;
        $r = json_decode($result);
        return isset($r->e->code) ? $r->e->code : false;
    }


    /**
     * 验证码校验
     * @param $code
     * @param $mobile
     * @return bool
     */
    public function message_verify_code($code,$mobile){
        $url = "http://".TP_Helper::array_random_element(TP_Config::get_sms('host'))."/sms/message/verify_code.json";
        $token = TP_Config::get_sms('token');
        $tcode = TP_Config::get_sms('tcode');
        $params = array(
            'token' => $token,//项目证书
            'code' => $code,//验证码
            'mobile' => $mobile,//手机号
            'tcode' => $tcode,//业务代码描述
            'ip' => TP_Helper::get_ip(),
        );
        $header = array();
        $request_timeout = TP_Config::get_sms('request_timeout');
        $result = TP_Curl::post($url,$params,$header,$request_timeout);
        TP_Log::sms("message_verify_code|url:".$url."|params:".http_build_query($params)."|ret:".$result);
        if(!$result) return false;
        $ret = json_decode($result);
        if(isset($ret->e->code) && $ret->e->code == 0){
            return true;
        }
        return false;
    }


}