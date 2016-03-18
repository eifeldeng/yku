<?php
class HttpRoute extends Route {
    /**
     * 路由管理
     *
     * @param string $uri            
     * @param array $appRoute            
     */
    public static function getRoute(string $uri, array &$appRoute) {
        $mvcArr = explode ( '/', $uri );
        if (isset ( $mvcArr [3] ) && $mvcArr [3] != '') {
            $appRoute ['module'] = isset ( $mvcArr [1] ) ? $mvcArr [1] : 'default';
            $appRoute ['controller'] = isset ( $mvcArr [2] ) ? $mvcArr [2] : 'default';
            $appRoute ['action'] = isset ( $mvcArr [3] ) ? $mvcArr [3] : 'index';
        } else {
            $appRoute ['controller'] = isset ( $mvcArr [1] ) ? $mvcArr [1] : 'default';
            $appRoute ['action'] = isset ( $mvcArr [2] ) ? $mvcArr [2] : 'index';
        }
    }
    public static function urlrouter_rewrite(&$uri, $verb = null) {
        // 读取配置文件
        $rewrite = Config::getConfig ( 'httpServer', 'Rewrite' );
        if (empty ( $rewrite ) or ! is_array ( $rewrite )) {
            return false;
        }
        $match = array ();
        $uri_for_regx = $uri;
        
        foreach ( $rewrite as $rule ) {
            // 'regx' => '^/(<controller>\w+)/(<action>\w+)\.html$', //这种是默认格式 其余都转到get里面
            // 如果设置了规则，并且传进来的不同 则pass 如果未设置，则不需要考虑
            if ((! empty ( $rule ['verb'] )) && ($verb != $rule ['verb'])) {
                continue;
            }
            // error_log(__LINE__." \r\n",3,'/tmp/router.log');
            $mvc = $rule ['mvc'];
            $mvcArr = explode ( '/', $mvc );
            if (count ( $mvcArr ) < 2) { // 如果小于2 则返回false
                return false;
            }
            $mvc = array ();
            $mvc ['controller'] = $mvcArr [0]; // 获取了controller 和 action
            $mvc ['action'] = $mvcArr [1];
            $tmp = array ();
            if (preg_match_all ( '/<\w+>/', $rule ['regx'], $match )) {
                foreach ( $match [0] as $k => $v ) { // 赋值到get参数内 按照顺序筛选出来 赋值出来key值
                    $tmp [] = trim ( $v, '<>' );
                }
            }
            ;
            
            $regx = preg_replace ( '/<\w+>/', '', $rule ['regx'] ); // 获得实际的正则表达式
            if (preg_match ( '#' . $regx . '#i', $uri_for_regx, $match )) {
                // 如果设置了mvc 则走指定的controller
                foreach ( $tmp as $k => $v ) {
                    if ($v == 'controller') {
                        $mvc ['controller'] = ucwords ( $match [$k + 1] ); // 获取了controller 和 action
                        continue;
                    }
                    if ($v == 'action') {
                        $mvc ['action'] = ucwords ( $match [$k + 1] );
                        continue;
                    }
                    
                    if ($v == 's_action') {
                        $mvc ['action'] .= ucwords ( $match [$k + 1] );
                    }
                    // 如果不是controller 也不是 action 则放入get参数中
                    $tmpGet [$v] = $match [$k + 1];
                    // $_GET[$v] = $match[$k + 1];
                }
                ;
                
                // 强制转为
                if (isset ( $tmpGet )) { // 如果设置了
                    $mvc ['get'] = array_merge ( $rule ['default'], ( array ) $tmpGet ); // 以tmpGet去覆盖default
                } else {
                    $mvc ['get'] = $rule ['default'];
                }
                
                return $mvc;
            }
        }
        return false;
    }
}
