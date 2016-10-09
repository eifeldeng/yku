<?php
/**
 * CURL http客户端程序
 *
 */
class YkuCurl {
    CONST DEFAULT_TIMEOUT = 1000;
    CONST HEADER_USER_AGENT = "Chrome/49.0.2587.3";
    CONST HEADER_ACCEPT = 'text/html,application/xhtml+xml,application/xml';
    CONST HEADER_ACCEPT_ENCODING = 'gzip';
    public static function get($url, $header = array(), $timeout = 2, &$errInfo = "") {
        $switch = self::curl_switch ( $url );
        if ($switch ['enable'] == 0) {
            return '';
        }
        
        $ssl = stripos ( $url, 'https://' ) === 0 ? true : false;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_HTTPGET, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_NOSIGNAL, 1 );
        if ($switch ['timeout'] > 0) {
            curl_setopt ( $ch, CURLOPT_TIMEOUT_MS, $switch ['timeout'] );
        } else {
            curl_setopt ( $ch, CURLOPT_TIMEOUT_MS, self::DEFAULT_TIMEOUT );
        }
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_MAXREDIRS, 3 );
        if ($ssl) {
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE ); // https请求 不验证证书和hosts
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
        }
        
        if (! empty ( $header )) {
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        }
        
        $result = curl_exec ( $ch );
        $httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        $errCode = curl_errno ( $ch );
        $errMsg = curl_error ( $ch );
        
        curl_close ( $ch );
        
        if ($errCode && $httpCode != 200) {
            $errInfo = "errno:{$errCode}|errMsg:{$errMsg}|httpCode:{$httpCode}|url:{$url}|heaer:" . json_encode ( $header );
            SysLog::error ( 'curl_error', $errInfo );
            return false;
        }
        return $result;
    }
    public static function post($url, $postdata, $header = array(), $timeout = 2) {
        $switch = self::curl_switch ( $url );
        if ($switch ['enable'] == 0) {
            return '';
        }
        
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        
        curl_setopt ( $ch, CURLOPT_NOSIGNAL, 1 );
        if ($switch ['timeout'] > 0) {
            curl_setopt ( $ch, CURLOPT_TIMEOUT_MS, $switch ['timeout'] );
        } else {
            curl_setopt ( $ch, CURLOPT_TIMEOUT_MS, self::DEFAULT_TIMEOUT );
        }
        
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postdata );
        
        if (! empty ( $header )) {
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        }
        
        $result = curl_exec ( $ch );
        $httpCode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
        $errCode = curl_errno ( $ch );
        $errMsg = curl_error ( $ch );
        
        curl_close ( $ch );
        
        if ($httpCode != 200) {
            if ($result == "") {
                $post_string = print_r ( $postdata, true );
                $errInfo = "errno:{$errCode}|errMsg:{$errMsg}|httpCode:{$httpCode}|url:{$url}|post_string:{$post_string}";
                SysLog::error ( 'curl_error', $errInfo );
                return false;
            }
        }
        
        return $result;
    }
    private static function curl_switch($url) {
        $path_info = parse_url ( $url, PHP_URL_PATH );
        $path_info = str_replace ( "//", "/", $path_info );
        $result = array (
                'timeout' => 0,
                'enable' => 1 
        );
        
        $outerserver_switcher_config = Config::loadConfig ( 'outerserver_switcher' );
        if (isset ( $outerserver_switcher_config [$path_info] )) {
            $result ['timeout'] = $outerserver_switcher_config [$path_info] ['timeout'];
            $result ['enable'] = $outerserver_switcher_config [$path_info] ['enable'];
        }
        return $result;
    }
    public  function coroutine_get($url, $port = 80, $header = array()) {
        $cli = new Swoole\Coroutine\Http\Client ( '10.111.88.109', 80 );
        $cli->setHeaders ( [ 
                'Host' => '10.111.88.109',
                "User-Agent" => 'Chrome/49.0.2587.3',
                'Accept' => 'text/html,application/xhtml+xml,application/xml',
                'Accept-Encoding' => 'gzip' 
        ] );
        $cli->set ( [ 
                'timeout' => 1 
        ] );
        $cli->get ( '/users/show.json?nick_name=GO%E6%9E%AB%E5%A4%A7%E5%8F%94' );
        $cli->close ();
        var_dump($cli->body);
         return  $cli->body;
    }
    public static function coroutine_post() {
    }
    
    /**
     * 装填swoole协程的header头
     *
     * @param unknown $header            
     * @param unknown $url_info            
     */
    private static function fill_header(&$header, $url_info) {
        if (! isset ( $header ['Host'] ) || $header ['Host'] == '') {
            $header ['Host'] = $url_info ['host'];
        }
        if (! isset ( $header ['User-Agent'] ) || $header ['User-Agent'] == '') {
            $header ['User-Agent'] = self::HEADER_USER_AGENT;
        }
        if (! isset ( $header ['Accept'] ) || $header ['Accept'] == '') {
            $header ['Accept'] = self::HEADER_ACCEPT;
        }
        if (! isset ( $header ['Accept-Encoding'] ) || $header ['Accept-Encoding'] == '') {
            $header ['Accept-Encoding'] = self::HEADER_ACCEPT_ENCODING;
        }
    }
}