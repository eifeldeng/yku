<?php
/**
 * Created by PhpStorm.
 * User: xiongkai
 * Date: 2015/6/25
 * Time: 15:41
 */

class TP_Weixin{


    private static $_instance;

    public static function get_instance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 用户同意授权，获取code
     * @param $appid
     * @param $redirect_uri
     */
    public function get_code($appid,$redirect_uri,$scope){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope={$scope}&state=STATE#wechat_redirect";
        header("Location:{$url}");
        exit;
    }

    /**
     * 通过code换取网页授权access_token
     * @param $appid
     * @param $appsecret
     * @param $code
     * @return bool|mixed
     */
    public function get_access_token($appid,$appsecret,$code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
        $ret = json_decode(TP_Curl::get($url));
        if(isset($ret->errcode) && $ret->errcode){
            TP_Log::error("get_access_token request|ret: faild | url:".$url);
            return false;
        }
        return $ret;
    }

    /**
     * 拉取用户信息(需scope为 snsapi_userinfo)
     * @param $access_token
     * @param $openid
     * @return bool|mixed
     */
    public function get_userinfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $ret = json_decode(TP_Curl::get($url));
        if(isset($ret->errcode) && $ret->errcode){
            TP_Log::error("get_userinfo request|ret: faild | url:".$url);
            return false;
        }
        return $ret;
    }

    /**
     * 获取jsapi_ticket
     * @param $access_token
     * @return bool
     */
    public function get_jsapi_ticket($access_token){
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $ret = json_decode(TP_Curl::get($url));
        if(isset($ret->errcode) && $ret->errcode == 0){
            TP_Log::error("get_jsapi_ticket request|ret: faild | url:".$url);
            return $ret->ticket;
        }else{
            return false;
        }
    }

    /**
     * 生成签名
     * @param $param
     * @return string
     */
    public function gen_js_sign($jsapi_ticket,$noncestr,$timestamp,$url){
        $sign_str = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
        $signature = sha1($sign_str);
        return $signature;
    }

    /**
     * 获取js config配置
     * @param $appid
     * @param $access_token
     * @param $url
     * @return array
     */
    public function get_js_config($appid,$access_token,$url){
        $jsapi_ticket = $this->get_jsapi_ticket($access_token);
        $noncestr = md5(microtime());
        $timestamp = time();
        $signature = $this->gen_js_sign($jsapi_ticket,$noncestr,$timestamp,$url);
        $config = array(
            'debug' => false,
            'appId' => $appid,
            'timestamp' => $timestamp,
            'nonceStr' => $noncestr,
            'signature' => $signature,
            'jsApiList' => array('onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','hideMenuItems'),
        );
        return $config;
    }

    /**
     * 获取微信access_token
     */
    public function get_wx_token(){
        $domain = TP_Config::get_activity('proxy_domain');
        $url = "http://".$domain."/proxy/get_wx_token.json?channel_id=102&platform=1";
        $ret = TP_Curl::get($url);
        $r = json_decode($ret,true);
        if(isset($r['error']) && $r['error'] == 1){
            return $r['token'];
        }else{
            TP_Log::error("get_wx_token request|ret:".$ret."|url:".$url);
            return false;
        }
    }

    /**
     * 获取微信openid
     * @param $appid
     * @param $appsecret
     * @param $redirect_uri
     * @return mixed
     */
    public function get_wx_openid($appid,$appsecret,$redirect_uri){
        if(!isset($_REQUEST['code'])){
            $this->get_code($appid,$redirect_uri,"snsapi_base");
        }

        $code = $_REQUEST['code'];
        $access_token_info = $this->get_access_token($appid,$appsecret,$code);
        $openid = $access_token_info->openid;

        return $openid;
    }

    /**
     * 获取微信openid用户头像昵称
     * @param $appid
     * @param $appsecret
     * @param $redirect_uri
     * @return bool|mixed
     */
    public function get_wx_user_info($appid,$appsecret,$redirect_uri){
        if(!isset($_REQUEST['code'])){
            $this->get_code($appid,$redirect_uri,"snsapi_userinfo");
        }

        $code = $_REQUEST['code'];
        $access_token_info = $this->get_access_token($appid,$appsecret,$code);
        $access_token = $access_token_info->access_token;
        $openid = $access_token_info->openid;

        $user_info = $this->get_userinfo($access_token,$openid);

        return $user_info;
    }

}