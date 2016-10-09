<?php
class Controller {
    protected $server;
    protected $argv = array ();
    protected $request;
    protected $fd;
    protected $from_fd;
    
    /**
     * 为了兼容，fd可以不传
     *
     * @param [type] $pbData
     *            [description]
     */
    function __construct($server, $argv = array(), $fd = 0, $from_fd = 0) {
        $this->server = $server;
        $this->argv = $argv;
        $this->fd = $fd;
        $this->from_fd = $from_fd;
    }
    
    // 初始化执行函数，支持自定义init
    public function init() {
        return true;
    }
    
    // 提前过滤
    protected function preFilter() {
        return true;
    }
    public function get_data($parameter, $default = '') {
        if (isset ( $this->argv ['request'] ['get'] [$parameter] )) {
            return $this->argv ['request'] ['get'] [$parameter];
        }
        return $default;
    }
    public function post_data($parameter, $default = '') {
        if (isset ( $this->argv ['request'] ['get'] [$parameter] )) {
            return $this->argv ['request'] ['get'] [$parameter];
        }
        return $default;
    }
    public function request_data($parameter, $default = '') {
        if (isset ( $this->argv ['request'] ['post'] [$parameter] )) {
            return $this->argv ['request'] ['post'] [$parameter];
        }
        if (isset ( $this->argv ['request'] ['get'] [$parameter] )) {
            return $this->argv ['request'] ['get'] [$parameter];
        }
        return $default;
    }
    /**
     * 返回参数
     *
     * @param unknown $data            
     */
    protected function render($data, $errno = 0, $errmsg = "success") {
        if (! is_string ( $data ) && ! is_numeric ( $data )) {
            $return_data = array (
                    'errno' => $errno,
                    'errmsg' => $errmsg,
                    'data' => $data 
            );
            $data = json_encode ( $return_data );
        }
        $response = $this->argv ['response'];
        $response->end ( print_r ( $data, true ) );
    }
}
