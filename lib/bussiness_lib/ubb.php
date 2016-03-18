<?php

class TP_UBB{

	/**
	 * 将源文本内容进行还原成html
	 */
	public static function ubbCode($str, $urls=array(), $isShort=false,$isGuestBook=false,$_blank=false){
		if(!is_array($urls)) $urls = array();
		
	    $str = trim($str);
		if($isShort) {
			$str = mb_substr($str, 0, 140, 'UTF-8'); 
			$str = preg_replace('/@[^@\s@<>&#?!=()[\].,:;]{0,15}$/sim', '', $str);
		}
		if(!empty($str)) {
	    	$str = htmlspecialchars($str);
		}
	    	
	    	$str = preg_replace('/\/\/@/i', ' //@', $str);
	    	$str = preg_replace('%([^\s .；,，:。;；/])@%i', '\1 @', $str);
		$str = preg_replace_callback('/@([^\s@<>&\#\?!=\(\)\[\]]{2,})((?:，)|(?:。)|(?:：)|(?:；)|(?: )|[ .,:;]|\/|$|[\n\r\t]|[\s@<>&\#\?!=\(\)\[\]])/Ui', function($matches){ return self::at(array(1=>$matches[1],2=>$matches[2]));}, $str);
		$str = preg_replace($isGuestBook ? '/[ ]+/i' : '/[\s]+/i', ' ', $str);
		$str = preg_replace('%([^\s .；,，:。;；/])@%i', '\1 @', $str);
		$str = preg_replace_callback('/\[quote\](.*)(写道)：[\n]*(.*)\[\/quote\]/is', function($matches){ return self::delQuote(array(1=>$matches[1],2=>$matches[2],3=>$matches[3]));}, $str);


		$str = str_replace(TP_MsgCenter::get_instance()->smiley_code, TP_MsgCenter::get_instance()->smiley_img, $str);
		$str = str_replace(TP_MsgCenter::get_instance()->chinese_code, TP_MsgCenter::get_instance()->smiley_img, $str);
		
		//替换汉字vip万万表情符号为表情
		$str = str_replace(TP_MsgCenter::get_instance()->chinese_code_wanwan,TP_MsgCenter::get_instance()->smiley_img_wanwan,$str);		
	    if($isGuestBook)$str = nl2br($str); 
	    return $str;
	}
		

	public static function getSpaceUrl($name){
		$error_url = "http://www.youku.com/index/y404";
		if(empty($name)) return $error_url;
		$a=TP_User::get_instance()->getUserByName($name);
		if (empty($a)) return $error_url;
		$uid= $a->id;
		return 'http://i.youku.com/u/'.TP_User::get_instance()->user_id_encode($uid);
	}

	/**
	  * author:wei.lu
	  * QComments - 解析回复贴中的@标记,考虑用户名包含@的情况
	  * @param : 回帖内容
	  */
	public static function at($matches,$_blank=false){
		$add=''; if($_blank) $add=' target="_blank" ';
		if(strpos($matches[1],'@') === false){
			return '<a _hz="s_userlink" class="YK_id" '.$add.' href="'.self::getSpaceUrl($matches[1]).'">@'.$matches[1].'</a>'.$matches[2];
		}
		$tmp = explode('@',$matches[1]);
		if(count($tmp) == 2 ){
			if(!is_null($userAPI->getUserByName($matches[1]))) return '<a _hz="s_userlink" class="YK_id" href="'.self::getSpaceUrl($matches[1]).'">@'.$matches[1].'</a>'.$matches[2];
			else return '<a _hz="s_userlink" href="javascript:;">@'.$tmp[0].'</a>'.'<a '.$add.' href="'.self::getSpaceUrl($tmp[1]).'">'.$tmp[1].'</a>'.$matches[2];
		}
		$str='';
		foreach($tmp as &$v){
			$str .='<a _hz="s_userlink" '.$add.' href="'.self::getSpaceUrl($v).'">@'.$v.'</a>';
		}
		return $str.$matches[2];
	}
	/**
	  *解析[quote][/quote]
	  *@param : 回帖内容
	  */
	public static function delQuote($matches){
		return '//<a _hz="s_userlink" href="'.self::getSpaceUrl($matches[1]).'">@'.$matches[1].'</a> ：'.$matches[3];
	}
}
?>
