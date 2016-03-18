<?php

class  TP_Member
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


	public function request($url_path,$param){
		$pub_key = TP_Config::get_member('pub_key');
		$sign_type = TP_Config::get_member('sign_type');
		$secretKey = TP_Config::get_member('secret_key');
		$urls = TP_Config::get_member('urls');
		$host = TP_Config::get_member('host');

		$param['pub_key'] = $pub_key;
		$param['sign_type'] = $sign_type;
		$param['sign'] = $this->create_sign($param,$secretKey,$sign_type);

        $url = TP_Helper::array_random_element($urls);
		$request_url = $url.$url_path.'?'.http_build_query($param);
		$header = array("Host: {$host}");

        $request_timeout = TP_Config::get_member('request_timeout');
		$resp_str = TP_Curl::get($request_url,$header,$request_timeout);
		$resp_arr = json_decode($resp_str,true);
		if($resp_arr['error'] != 1){
			$errInfo = Config_Errno::ERR_MEMBER_REQUEST.'|'.Config_Errmsg::ERR_MEMBER_REQUEST.'|';
			$errInfo .= "member_request_url:{$request_url}|resp_str:{$resp_str}";
			TP_Log::error($errInfo);
			return;
		}else{
			return $resp_arr['result'];
		}
	}

	/**
	* 会员权益接口
	*/
	public function service_show($uid)
	{
		$param = array(
			'uid' => $uid,
			'platform' => TP_Config::get_member('platform'),
		);
		$url_path = TP_Config::get_member('service_show_path');
		$result = $this->request($url_path,$param);
		return $result;
	}

	/**
	* 会员信息接口
	*/
	public function show_info($uid)
	{
		$param = array(
			'uid' => $uid,
			'platform' => TP_Config::get_member('platform'),
			'member_id' => 0,
		);
		$url_path = TP_Config::get_member('show_info_path');
		$result = $this->request($url_path,$param);
		return $result;
	}
	public function show_valid_member($ytid)
	{
		$param = array(
			'ytid' => $ytid,
			'leveltype' => 1,
			);
		$url_path = TP_Config::get_member('show_valid_member_path');
		$result = $this->request($url_path,$param);
		return $result;

	}

	/**
	* 所有会员权益接口列表
	*/
	public function service_list()
	{
		$param = array();
		$url_path = TP_Config::get_member('service_list_path');
		$result = $this->request($url_path,$param);
		return $result;
	}

	/**
	* 单个会员权益获取接口
	*/
	public function service_get($serid)
	{
		$param = array(
			'serid' => $serid,
		);
		$url_path = TP_Config::get_member('service_get_path');
		$result = $this->request($url_path,$param);
		return $result;
	}

	/**
	* 会员权益验证接口
	*/
	public function service_verify($uid,$serids)
	{
		$param = array(
			'serids' => $serids,
			'uid' => $uid,
			'platform' => TP_Config::get_member('platform'),
			'ip' => ip2long(TP_Helper::get_ip()),
			'is_wrt_log' => 0,
		);
		$url_path = TP_Config::get_member('service_verify_path');
		$result = $this->request($url_path,$param);
		return $result;
	}

	/**
	* 我的观影券列表
	*/
	public function tickets_list($uid)
	{
		$platform = TP_Config::get_member('platform');
		$pub_key = TP_Config::get_member('tickets_pub_key');
		$sign_type = TP_Config::get_member('tickets_sign_type');
		$param = array(
			'uid' => $uid,
			'state' => 1,
			'psize' => 20,
			'pnumber' => 10,
			'platform' => $platform,
			'pub_key' => $pub_key,
			'sign_type' => $sign_type,
		);
		$secretKey = TP_Config::get_member('tickets_secret_key');
		$param['sign'] = $this->create_sign($param,$secretKey,$sign_type);
		
		$request_url = TP_Config::get_member('tickets_url').TP_Config::get_member('tickets_list_path');
		$host = TP_Config::get_member('tickets_host');
		$header = array("Host: {$host}");

        $request_timeout = TP_Config::get_member('request_timeout');
		$resp_str = TP_Curl::post($request_url,$param,$header,$request_timeout);
		$resp_arr = json_decode($resp_str,true);
		if($resp_arr['error'] != 1){
			$errInfo = Config_Errno::ERR_TICKETS_REQUEST.'|'.Config_Errmsg::ERR_TICKETS_REQUEST.'|';
			$errInfo .= "tickets_request_url:{$request_url}|param:".json_encode($param)."|resp_str:{$resp_str}";
			TP_Log::error($errInfo);
			return;
		}else{
			return $resp_arr;
		}
	}

    /**
     * 根据用户id查询自动续费账户接口
     * @param $ytid
     */
    public function get_auto_renew_account_by_id($ytid){
        if(!$ytid){
            return false;
        }

        $url = "http://".TP_Helper::array_random_element(TP_Config::get_member('trade_ip'))."/account/get_auto_renew_account_by_id";
        $platform = TP_Config::get_member('platform');
        $pub_key = TP_Config::get_member('trade_pub_key');
        $sign_type = TP_Config::get_member('trade_sign_type');
        $post_params = array(
            'ytid' => $ytid,
            'platform' => $platform,
            'pub_key' => $pub_key,
            'sign_type' => $sign_type,
        );
        $secret_key = TP_Config::get_member('trade_secret_key');
        $post_params['sign'] = $this->create_sign($post_params,$secret_key,$sign_type);
        $header = array("Host: ".TP_Config::get_member('trade_host'));
        $request_timeout = TP_Config::get_member('request_timeout');
        $result = TP_Curl::post($url,$post_params,$header,$request_timeout);
        $ret = json_decode($result);
        if(isset($ret->error) && $ret->error == 1){
            return true;
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

		// 去掉最后一个 "&"
		$signPars = substr($signPars, 0, strlen($signPars)-1);

	    $sign = strtolower(hash_hmac($algo, $signPars, $secretKey));

	    //echo "algo:{$algo}|signPars:{$signPars}|secretKey:{$secretKey}|sign:{$sign}";

		return $sign;
	}

	/**
	* 判断用户是否是轻会员
	*/
	public function is_lvip($uid){
		$showInfoRes = $this->show_info($uid);
		foreach ($showInfoRes as $v) {
			if($v['member_id'] == TP_Config::get_member('lvip_flag')){
				TP_Log::info('is_lvip');
				return $v;
			}
		}
		return false;
	}

    /**
     * 查询过期会员从exptime至当前的最后一次过期时间
     * @param $uid
     * @return bool|void
     */
    public function show_last_exp_info($uid){
        if(!$uid){
            return false;
        }
        $url = TP_Helper::array_random_element(TP_Config::get_member('urls'))."/member/show_last_exp_info.json";
        $platform = TP_Config::get_member('platform');
        $pub_key = TP_Config::get_member('pub_key');
        $sign_type = TP_Config::get_member('sign_type');
        $post_params = array(
            'uid' => $uid,
            'platform' => $platform,
            'exptime' => strtotime("-15 day"),
            'pub_key' => $pub_key,
            'sign_type' => $sign_type,
        );
        $secret_key = TP_Config::get_member('secret_key');
        $post_params['sign'] = $this->create_sign($post_params,$secret_key,$sign_type);
        $header = array();
        $request_timeout = TP_Config::get_member('request_timeout');
        $result = TP_Curl::post($url,$post_params,$header,$request_timeout);
        $ret = json_decode($result,true);
        if(isset($ret['error']) && $ret['error'] == 1){
            return $ret['result'];
        }else{
            return false;
        }
    }

}
