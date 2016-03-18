<?php

class TP_Upload
{
	public static function genKey($type,$subtype)
	{
		//$bstr = pack('cc',$type,$subtype);
		$time = time(NULL);
		$pid = posix_getpid();
		$ip = empty($_SERVER['SERVER_ADDR']) ? '127.0.0.1' : $_SERVER['SERVER_ADDR'];
		$rand = rand();

		$ip_arr = explode('.',$ip);

		$ip_1 = empty($ip_arr[1]) ? 0 : $ip_arr[1];
		$ip_2 = empty($ip_arr[2]) ? 0 : $ip_arr[2];
		$ip_3 = empty($ip_arr[3]) ? 0 : $ip_arr[3];

		$bstr = pack('c4Nc3nc3',$type,$subtype,0x0d,0x01,$time,$ip_1,$ip_2,$ip_3,$pid,$rand & 0xff,($rand >> 8) & 0xff,($rand >> 16) & 0xff);

		$hexstr = strtoupper(bin2hex($bstr));
		return $hexstr;
	}

	//默认typ=1表示从二进制数据直接上传，type=2表示从文件上传，bin_data为文件名
	//upload_type = 1上传图片，2 普通文件
	public static function upimage($bin_data,&$filekey,$type = 1,$upload_type = 1)
	{
		if($upload_type == 1)
		{
			$key = self::genKey(0x05,0xff);
		}
		else
		{
			$key = self::genKey(0x06,0x01);
		}
		$filekey = $key;
		$ch = curl_init();
		curl_setopt ( $ch ,  CURLOPT_URL ,  "http://10.100.188.155/storagetracker/tracker/put.json?ns=res&key=$key");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);

		$json = json_decode($data,true);
		if(empty($json))
		{
			TP_Log::err("storagetracker put error");
			return -1;
		}

		if($json["e"]["code"] != 0)
		{
			TP_Log::err("storagetracker put error" . $json["e"]["code"] != 0);
			return -1;
		}

		$fid = $json["data"]["fid"];
		$devid = $json["data"]["devid"];
		$path = $json["data"]["path"];

		$path_arr  = explode(":",$path);

		$server = str_replace("//","",$path_arr[1]);
		$pos = strpos($path_arr[2],'/');

		$port = substr($path_arr[2],0,$pos);
		$path_tmp = substr($path_arr[2],$pos);

		$wdc = new TP_Webdavclient();
		$wdc->set_server($server);
		$wdc->set_port($port);
		$wdc->set_protocol(1);

		$iRet = $wdc->open();
		if(empty($iRet))
		{
			TP_Log::err("webdav error" . $iRet);
			return -2;
		}

		if($type == 1)
		{
			$wdc->put($path_tmp,$bin_data);
			$size = strlen($bin_data);
		}
		else
		{
			$wdc->put_file($path_tmp,$bin_data);
			$size = filesize($bin_data);
		}
		/*
		$res = webdav_connect($path);
		webdav_put('',file_get_contents('./a.jpg'),$res);
		var_dump($res);
		 */


		curl_setopt ( $ch ,  CURLOPT_URL ,  "http://10.100.188.155/storagetracker/tracker/confirm.json?ns=res&key=$key&path=$path&fid=$fid&devid=$devid&size=$size");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);
		$data = json_decode($data,true);

		curl_close($ch);
		return $data["e"]["code"];
	}
}
