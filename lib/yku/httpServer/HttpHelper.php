<?php
class HttpHelper {
    
    // 解析成功
    const HTTP_OK = 0x00;
    // 请求方式错误
    const HTTP_ERROR_METHOD = 0x01;
    // 请求uri错误
    const HTTP_ERROR_URI = 0x02;
    
    /**
     * 处理request对象
     *
     * @param
     *            req swoole http server 获得的request对象
     */
    public static function httpReqHandle($req) {
        $method = $req->server ['request_method'];
        $uri = $req->server ['request_uri'];
        // 正则匹配的路由 支持restful 提供给深度用户使用
        // $appRoute = HttpRoute::urlrouter_rewrite($uri,$method);
        // explode 解析类似于 controller/action类型的url
        
        // 默认会解析到default/index
        
        
        $appRoute = array (
                'module' => '',
                'controller' => '',
                'action' => '' 
        );
       HttpRoute::getRoute($uri, $appRoute);
        return array (
                'r' => self::HTTP_OK,
                'route' => $appRoute,
                'request' => array (
                        'uri' => $uri,
                        'header' => $req->header,
                        'get' => isset ( $req->get ) ? $req->get :'',
                        'post' => (isset ( $req->post )) ? $req->post : '',
                        'files' => isset ( $req->files ) ? $req->files : '',
                        'cookie' => isset ( $req->cookie ) ? $req->cookie : '',
                        'rawcontent' => $req->rawContent (),
                        'method' => $method 
                ) 
        );
    }
}
