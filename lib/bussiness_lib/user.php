<?php

class TP_User
{

	public static $_instance;

	public static function get_instance()
	{
		if(!self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function test(){

		echo 'Service_User<br/>';
	}

	/**
	* 从cookie中获取验证的yktk参数
	*/
	public function get_yktk(){
		$yktk = TP_Request::get_cookie('yktk');
		$yktk = urldecode($yktk);
		$yktk = str_replace(' ', '+', $yktk);
		return $yktk;
	}

	/**
	* 判断用户是否登录
	*/
	public function is_login($yktk){
		if(!$yktk){
			return false;
		}

		$login_verify = $this->login_verify($yktk);
		if(!$login_verify){
			TP_Log::error(Config_Errno::ERR_USER_LOGIN_VERIFY."|".Config_Errmsg::ERR_USER_LOGIN_VERIFY);
			return false;
		}

		return true;
	}

	/**
	* 通过yktk解析出用户信息
	*/
	public function get_user_info_by_yktk($yktk){
        $id = $nn = $vip = $ytid = "";
		$yktk = explode('|', $yktk);
        if(isset($yktk[3])){
            $array = explode(',',base64_decode($yktk[3]));
            foreach($array as $val){
                $v = explode(':', $val);
                if($v[0] == 'id'){
                    $id = $v[1];
                }elseif($v[0] == 'nn'){
                    $nn = $v[1];
                }elseif($v[0] == 'vip'){
                    $vip = $v[1];
                }elseif($v[0] == 'ytid'){
                    $ytid = $v[1];
                }
            }
        }
		$userinfo = array('id'=>$id,'nn'=>$nn,'vip'=>$vip,'ytid'=>$ytid);
		return $userinfo;
	}

	public function getUserByName($nickname){
		$UC_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
		$url = 'http://'.$UC_API_SERVER.'/users/show.json?nick_name='.$nickname;
		$ret = json_decode(TP_Curl::get($url));
		return isset($ret->user)?$ret->user:null;
	}
	public function updateName($params)
	{
		$ip = TP_Config::get_user('passport_url');
		$url = $ip.'/passport/update_name?'.http_build_query($params);

		$result = TP_Curl::get($url);
		if($result === false)
		{
			return array();
		}
		$ret = json_decode($result,true);
		return $ret;
	}

	public function checkName($nickname)
	{
		$ip = TP_Config::get_user('passport_url');
		$url = $ip.'/passport/check_username?nickname='.$nickname;
		$result = TP_Curl::get($url);
		if($result === false)
		{
			return -1;
		}
		$ret = json_decode($result,true);
		return empty($ret["errno"]) ? 0 : $ret["errno"];
	}
	public function updateAvatar($params)
	{
		$ip = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
		$url = 'http://'.$ip.'/account/update?'.http_build_query($params);
		$result = TP_Curl::get($url);
		if($result === false)
		{
			return -1;
		}
		$ret = json_decode($result,true);
		if($ret["error"] == 1)
		{
			return 0;
		}
		else if($ret["error"] == 0)
		{
			return -2;
		}
		return $ret["error"];
	}


	//用户头像
	//large big middle small
	public function getUserAvatar($uid){
		$UC_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
		$url = 'http://'.$UC_API_SERVER.'/users/show_avatar.json?uid='.$uid;
		$ret = json_decode(TP_Curl::get($url));		
		return isset($ret->avatar)?$ret->avatar:null;
	}

	/**
	* 通过用户ID获取详细的用户信息
	*/
	public function get_user_info_by_uid($uid){
        $user_server_ip = TP_Helper::array_random_element(TP_Config::get_user('user_server_ip'));
		$request_url = "http://".$user_server_ip.TP_Config::get_user('show_path').'?uid='.$uid;
        $request_timeout = TP_Config::get_user('request_timeout');
		$resp_str = TP_Curl::get($request_url,array(),$request_timeout);
		$resp_arr = json_decode($resp_str,true);
		if($resp_arr['error'] != 1){
			$errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
			$errInfo .= "user_request_url:{$request_url}|resp_str:{$resp_str}";
			TP_log::error($errInfo);
			return array();
		}else{
			return $resp_arr['user'];
		}
	}

	/**
	* 获取登录态校验失败后重定向的URL地址
	*/
	public function get_logout_url($url=''){
		$cburl = $url ? $url : 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $link = stripos($cburl,'?') !== false ? "&" : "?";
        $cburl = "$cburl{$link}retry=1";
		$logoutUrl = "http://login.youku.com/user_login?cburl=".urlencode($cburl);
		return $logoutUrl;
	}

	/**
	* 用户退出登录
	*/
	public function logout(){
		$logoutUrl = $this->get_logout_url();
		exit('<script>location.href="'.$logoutUrl.'"</script>');
	}

    /**
     * 用户登陆
     * 默认登陆成功后跳转到当前的url地址
     * @param $cburl
     */
    public function user_login($url = ''){
        setcookie('yktk','',time()-3600,'/','youku.com');
        $current_url = TP_Request::get_current_url();
        $cburl  = $url ? $url : $current_url;
        $user_login_url = "http://login.youku.com/user_login?cburl=".urlencode($cburl);
        exit('<script>location.href="'.$user_login_url.'"</script>');
    }

	/**
	* 用户在会员详细页面退出登录
	*/
	public function info_logout(){
		$info_callback_url = TP_Config::get_member('info_callback_url');
		$logoutUrl = $this->get_logout_url($info_callback_url);
		exit('<script>top.location.href="'.$logoutUrl.'"</script>');
	}


	/**
	* 登录校验
	*/
	public function login_verify($yktk)
	{
        //20150723 由于北京测试环境的登录态无法使用正式环境代码校验，特增加simulation配置，在仿真环境不进行cookie合法性校验
        if(SERVER_TYPE == 'simulation'){
            return true;
        }
		$auths = array('hash_verify','passport_auth');
		foreach($auths as $auth){
			$verify_res = $this->$auth($yktk);
			if($verify_res){
				break;
			}
		}

		if(!$verify_res){
			return false;
		}

		return true;
	}

	/**
	* 登录本地hash校验
	*/
	public function hash_verify($yktk)
	{
		$arr = explode('|', $yktk);
		$hash = $arr[4];
		$hash_key = TP_Config::get_user('passport_hash_key');
		$hash_str = $arr[0].'|'.$arr[1].'|'.$arr[2].'|'.$arr[3].'|'.$hash_key;
		$server_hash = md5($hash_str);
		if($hash == $server_hash){
			return true;
		}else{
			$errInfo = Config_Errno::ERR_USER_HASH_VERIFY."|".Config_Errmsg::ERR_USER_HASH_VERIFY.'|';
			$errInfo .= "server_hash:{$server_hash}|hash:{$hash}|hash_str:{$hash_str}";
			TP_Log::error($errInfo);
			return false;
		}
	}

	/**
	* 登录passport auth接口校验
	*/
	public function passport_auth($yktk)
	{
		$passport_auth_url = TP_Config::get_user('passport_url').TP_Config::get_user('passport_auth_path').'?cookie='.urlencode($yktk);
        $request_timeout = TP_Config::get_user('request_timeout');
		$result = TP_Curl::get($passport_auth_url,array(),$request_timeout);
		$res = json_decode($result,true);
		if($res['errno'] === 0){
			return true;
		}else{
			$errInfo = Config_Errno::ERR_USER_PASSPORT_AUTH."|".Config_Errmsg::ERR_USER_PASSPORT_AUTH."|";
			$errInfo .= "passport_auth_fail|result:".$result."|url:{$passport_auth_url}";
			TP_Log::error($errInfo);
			return false;
		}
	}

    /**
     * 判断用户是否是VIP
     * @param $uid
     * @return bool
     */
    public function is_vip($uid){
        $request_url = TP_Config::get_user('passportext_url').TP_Config::get_user('passportext_is_vip_path').'?uids='.$uid;
        $request_timeout = TP_Config::get_user('request_timeout');
        $resp_str = TP_Curl::get($request_url,array(),$request_timeout);
        $resp_arr = json_decode($resp_str,true);
        $is_vip = false;
        if($resp_arr['errno'] === 0){
            $data = $resp_arr['data'];
            foreach($data as $v) {
                if ($v['uid'] == $uid) {
                    $is_vip = $v['vip'];
                }
            }
        }else{
            $errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
            $errInfo .= "passportext_request_fail:{$request_url}|resp_str:{$resp_str}";
            TP_log::error($errInfo);
        }
        return $is_vip;
    }

    /**
     * 获取用户类型
     * @param $uid
     * @return int
     */
    public function get_user_type($uid){
        $user_type = Config_Const::USER_NORMAL;
        $is_vip = $this->is_vip($uid);
        if($is_vip){
            $showInfoRes = TP_Member::get_instance()->show_info($uid);
            $is_mvip = $is_lvip = false;
            foreach ($showInfoRes as $v) {
                if($v['member_id'] == TP_Config::get_member('lvip_flag')){
                    $is_lvip = true;
                }
                if($v['member_id'] == TP_Config::get_member('mvip_flag')){
                    $is_mvip = true;
                }
            }

            if($is_mvip && $is_lvip){
                $user_type = Config_Const::USER_VIP_LVIP;
            }elseif($is_mvip){
                $user_type = Config_Const::USER_VIP;
            }elseif($is_lvip){
                $user_type = Config_Const::USER_LVIP;
            }
        }

        return $user_type;
    }

    public function batch_get_user_type($uid_list){
        $request_url = TP_Config::get_user('passportext_url').TP_Config::get_user('passportext_is_vip_path').'?uids='.$uid_list;
        $request_timeout = TP_Config::get_user('request_timeout');
        $is_vip_rsp = json_decode(TP_Curl::get($request_url,array(),$request_timeout),true);
        $user_type_list = array();
        if(!empty($is_vip_rsp)){
            foreach($is_vip_rsp['data'] as $v){
                $user_type_list[$v['uid']]['vip'] = $v['vip'];
            }
        }
        return $user_type_list;
    }

	public function batch_get_user_ytid($uid_list){
		$ytid_list=array();
		$request_url = TP_Config::get_user('passport_url').'/passport/get_by_id?ids='.$uid_list;
		try{		
			$request_timeout = TP_Config::get_user('request_timeout');
			$result = TP_Curl::get($request_url,array(),$request_timeout);
			$res = json_decode($result,true);
			if($res['errno'] === 0){
				$data_list=$res['data'];
				if(!empty($data_list)){
					foreach($data_list as $v){
						$ytid_list[$v['uid']] = $v['ytid'];
					}
				}
			}
		}catch(Exception $e){
			$errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
	            $errInfo .= "passportext_request_fail:{$request_url}|resp_str:".$e;
	            TP_log::error($errInfo);
		}
		return $ytid_list;
	}

	
	public function batch_get_user($uid_list){
		$UC_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
		$request_url = 'http://'.$UC_API_SERVER.'/users/batch_show.json?uids=['.$uid_list.']';
		try{		
			$request_timeout = TP_Config::get_user('request_timeout');
			$result = TP_Curl::get($request_url,array(),$request_timeout);
			$res = json_decode($result,true);
			if($res['error'] === 1){
				return $res['users'];
			}
		}catch(Exception $e){
			$errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
	            $errInfo .= "ucenter_batch_show.json:{$request_url}|resp_str:".$e;
	            TP_log::error($errInfo);
		}
		return null;
	}

	public function batch_get_user_info($uid_list){
		$request_url = TP_Config::get_user('passport_url').'/passport/get_by_id?ids='.$uid_list;
		try{		
			$request_timeout = TP_Config::get_user('request_timeout');
			$result = TP_Curl::get($request_url,array(),$request_timeout);
			$res = json_decode($result,true);
			if($res['errno'] === 0){
				return $res['data'];
			}
		}catch(Exception $e){
			$errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
	            $errInfo .= "passportext_request_fail:{$request_url}|resp_str:".$e;
	            TP_log::error($errInfo);
		}
		return null;
	}

    public function get_user_vip_type($uid){
        $user_vip_type = array();
        $user_vip_type[$uid]['vip'] = false;
        $user_vip_type[$uid]['lvip'] = false;
        $showInfoRes = TP_Member::get_instance()->show_info($uid);
        if(!empty($showInfoRes)){
            foreach ($showInfoRes as $v) {
                if($v['member_id'] == TP_Config::get_member('lvip_flag')){
                    $user_vip_type[$uid]['lvip'] = true;
                    $user_vip_type[$uid]['lvip_exptime'] = $v['exptime'];
                }
                if($v['member_id'] == TP_Config::get_member('mvip_flag')){
                    $user_vip_type[$uid]['vip'] = true;
                    $user_vip_type[$uid]['vip_exptime'] = $v['exptime'];
                }
                $user_vip_type[$uid]['ytid'] = $v['ytid'];
            }
        }
        return $user_vip_type;
    }

	/*
	 * 验证用户uid合法性
	 */
	public function uid_verify($uid,$cookie){
		$yktk = str_replace(' ', '+', urldecode($cookie));

		$yktk_arr = explode('|', $yktk);
		$array = explode(',',base64_decode($yktk_arr[3]));
		foreach($array as $val){
			$v = explode(':', $val);
			if($v[0] == 'id'){
				if($uid != $v[1]){
					return false;
				}
			}
		}

		$hash = $yktk_arr[4];
		$hash_key = TP_Config::get_user('passport_hash_key');
		$hash_str = $yktk_arr[0].'|'.$yktk_arr[1].'|'.$yktk_arr[2].'|'.$yktk_arr[3].'|'.$hash_key;
		$server_hash = md5($hash_str);
		if($hash == $server_hash){
			return true;
		}else{
			$errInfo = Config_Errno::ERR_USER_HASH_VERIFY."|".Config_Errmsg::ERR_USER_HASH_VERIFY.'|';
			$errInfo .= "server_hash:{$server_hash}|hash:{$hash}|hash_str:{$hash_str}";
			TP_Log::error($errInfo);

			return $this->passport_auth($yktk);
		}

	}

    /**
     * 查询用户积分
     * @param $uid
     * @return bool|int
     */
    public function get_user_integral($uid){
        if (!$uid || !is_numeric($uid)) {
            return false;
        }
        $UTS_SERVERS = TP_Helper::array_random_element(TP_Config::get_grade('uts_servers'));
        $url = 'http://'.$UTS_SERVERS.'/uts/user/get_user_integral.json';
        $url .= '?uid='.$uid;

        try{
            $ret = json_decode(TP_Curl::get($url));
            if(is_object($ret) && isset($ret->e->code) && $ret->e->code>=0){
                $inte = !empty($ret->inte) ? $ret->inte : 0;
                return $inte;
            }else{
                return 0;
            }
        }catch(Exception $e){
            return 0;
        }
    }

    /**
     * passport获取用户信息-邮箱-手机号等
     * @param $ids
     * @return bool
     */
    public function passport_get_by_id($ids,$user_type = 0){
        $passport_url = TP_Config::get_user('passport_url');
        $url = $passport_url."/passport/get_by_id?ids=".$ids."&user_type=".$user_type;
        $result = TP_Curl::get($url);
        $r = json_decode($result);
        if(!is_object($r) || !isset($r->errno) || $r->errno != 0)
        {
            $errInfo = Config_Errno::ERR_USER_REQUEST.'|'.Config_Errmsg::ERR_USER_REQUEST.'|';
            $errInfo .= "passport_get_by_id|url:{$url}|resp_str:".$result;
            TP_log::error($errInfo);
            return false;
        }
        else
        {
            return isset($r->data[0])?$r->data[0]:false;
        }
    }

    /**
     * 根据mobile查询用户信息
     * @param $mobile
     * @param int $user_type
     * @return bool
     */
    public function passport_get_by_mobile($mobile,$user_type = 0){
        $passport_url = TP_Config::get_user('passport_url');
        $url = $passport_url."/passport/get_by_mobile?mobile=".$mobile."&user_type=".$user_type."&ip=".TP_Helper::get_ip();
        $result = TP_Curl::get($url);
        $r = json_decode($result);
        if(!is_object($r) || !isset($r->errno) || $r->errno != 0) {
            TP_log::error("passport_get_by_id|url:{$url}|resp_str:".$result);
            return false;
        } else {
            return isset($r->data) ? $r->data : false;
        }
    }


    /**
     * 获取认证用户信息
     * @param $uid
     * @return bool
     */
    public function custom_vip_user($uid){
        $url = TP_Config::get_user('url');
        $url .= "/users/custom/vip_user.json?uid=".$uid;
        $ret = json_decode(TP_Curl::get($url));
        if(is_object($ret) && isset($ret->e->code) && $ret->e->code==1) return $ret->data;
        return false;
    }


/**
     * 查询一个源用户与一个目标用户之间的关注关系 
     * @param $source_uid
     * @param $target_uid
     * @return int // 0未关注 1关注 2互相关注
     */
 public function get_user_friendship($source_uid,$target_uid){
    $friendship = 0;
    try {
        $UC_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
        $url = 'http://'.$UC_API_SERVER.'/friendships/show.json';
        $url .= '?source_uid='.$source_uid.'&target_array_uid=['.$target_uid.']&all=1';
        $ret = json_decode(TP_Curl::get($url),true);
        if(isset($ret['error']) && $ret['error']==1){
            $target = $ret['target'][0];
            if($target['followed']==true){
                $friendship=$target['following'] == true ? 2: 1;
            }
        }
    } catch (Exception $e) {}
    return $friendship;
 }



    /**
     * uid encode
     * @param $uid
     * @return string
     */
    public function user_id_encode($uid){
        return 'U'.base64_encode($uid<<2);
    }


    const USER_ID_PREFIX = 'U';
	/**
	 * 恢复原有id
	 *
	 * @param unknown_type $encodeId
	 * @return unknown
	 */
	public static function decode($encodeId){
		if(empty($encodeId)) return '';
		$encodeId = preg_replace("/^c([a-z]{1})([0-9]+)/i","",$encodeId);
		if(self::isEncode($encodeId)){
			$userId = (base64_decode(self::dewrap($encodeId)))>>2;
			return $userId;
		}else{
			return $encodeId;
		}
	}

	/**
     * 检查当前会员ID是否为编码后的ID
     *
     * @param string $videoId
     * @return bool
     */
	public static function isEncode($userId){
		if(self::isWrapped($userId)){
			return true;
		}
		return false;
	}

	/**
	 * 取消附带的编码标识
	 *
	 * @param string $encodeId
	 * @return string
	 */
	private static function dewrap($encodeId){
		if(self::isWrapped($encodeId)){
			return substr($encodeId,strlen(self::USER_ID_PREFIX));
		}
		return $encodeId;
	}

	/**
	 * 检查是否附带编码标识
	 *
	 * @param int $userId
	 * @return bool
	 */
	private static function isWrapped($userId){
		if(stripos($userId,self::USER_ID_PREFIX)===0){
			return true;
		}else{
			return false;
		}
	}

    /**
     * 更新用户手机号码
     * @param $uid
     * @param $mobile
     * @return bool|mixed
     */
    public function passport_update_mobile($uid,$mobile){
        $url = TP_Config::get_user('passport_url')."/passport/update_mobile";
        $params = array('uid' => $uid, 'mobile' => $mobile);
        $url .= '?'.http_build_query($params);
        $header = array();
        $request_timeout = TP_Config::get_user('request_timeout');
        $result = TP_Curl::get($url,$header,$request_timeout);
        TP_Log::sms("passport_update_mobile|url:".$url."|ret:".$result);
        if(!$result) return false;
        $r = json_decode($result);
        return isset($r->errno) ? $r->errno : false;
    }

    /**
     * 根据用户id设置用户feed信息
     * @param $uid
     * @param $type
     * @param $status
     * @return bool
     */
    public function feed_set_abandon($uid,$type,$status){
        $feed_url = TP_Helper::array_random_element(TP_Config::get_user('feed_url'));
        $url = "http://{$feed_url}/feed.stream/show/setAbandon.json?uid={$uid}&type={$type}&status={$status}";
        $header = array();
        $request_timeout = TP_Config::get_user('request_timeout');
        $result = TP_Curl::get($url,$header,$request_timeout);
        if(!$result) return false;
        return json_decode($result);
    }

    /**
     * 根据用户id获取用户feed信息
     * @param $uid
     * @return bool
     */
    public function feed_get_abandon($uid){
        $feed_url = TP_Helper::array_random_element(TP_Config::get_user('feed_url'));
        $url = "http://{$feed_url}/feed.stream/show/getAbandon.json?uid={$uid}";
        $header = array();
        $request_timeout = TP_Config::get_user('request_timeout');
        $result = TP_Curl::get($url,$header,$request_timeout);
        if(!$result) return false;
        return json_decode($result);
    }

    /**
     * 更新用户优酷个性域名
     * @param $uid
     * @param $name
     * @return bool|mixed
     */
    public function passport_update_domain($uid,$domain){
        $url = TP_Config::get_user('passport_url')."/passport/update_domain?uid={$uid}&domain={$domain}";
        $header = array();
        $request_timeout = TP_Config::get_user('request_timeout');
        $result = TP_Curl::get($url,$header,$request_timeout);
        if(!$result) return false;
        return json_decode($result);
    }

    /**
     * 修改账户信息
     * @param $uid
     * @param array $params
     * @return array|bool|mixed|stdClass
     */
    public function account_update($uid,$params = array()){
        if(empty($params) || !is_array($params)){
            return false;
        }
        $user_url = TP_Helper::array_random_element(TP_Config::get_user('user_server_ip'));
        $url = "http://".$user_url."/account/update.json?uid={$uid}";
        foreach($params as $k=>$v){
            $url .= "&".$k."=".$v;
        }
        $header = array();
        $request_timeout = TP_Config::get_user('request_timeout');
        $result = TP_Curl::get($url,$header,$request_timeout);
        if(!$result) return false;
        return json_decode($result);
    }


}