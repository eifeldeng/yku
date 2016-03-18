<?php
/**
 * CURL http客户端程序
 *
 */
class TP_Curl
{

    public static function get($url, $header = array(), $timeout = 5)
    {
        $ssl = stripos($url,'https://') === 0 ? true : false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if($ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        
        if (!empty($header))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $errCode = curl_errno($ch);
        $errMsg = curl_error($ch);

        curl_close($ch);

        if($errCode && $httpCode != 200)
        {
            $errInfo = "errno:{$errCode}|errMsg:{$errMsg}|httpCode:{$httpCode}|url:{$url}|heaer:".json_encode($header);
            TP_Log::error($errInfo);
            return false;
        }

        return $result;
    }

    public static function post($url, $postdata, $header=array(), $timeout=10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, true);

        $post_array = array();
        if(is_array($postdata))
        {
            foreach($postdata as $key=>$value)
            {
                $post_array[] = urlencode($key) . "=" . urlencode($value);
            }

            $post_string = implode("&",$post_array);
        }
        else
        {
            $post_string = $postdata;
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);

        if (!empty($header))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $errCode = curl_errno($ch);
        $errMsg = curl_error($ch);

        curl_close($ch);

        if($httpCode != 200)
        {
            $errInfo = "errno:{$errCode}|errMsg:{$errMsg}|httpCode:{$httpCode}|url:{$url}|post_string:{$post_string}";
            TP_Log::error($errInfo);
            return false;
        }

        return $result;
    }
    


}