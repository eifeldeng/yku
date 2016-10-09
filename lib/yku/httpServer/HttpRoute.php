<?php
class HttpRoute extends Route {
    /**
     * 路由管理
     *
     * @param string $uri            
     * @param array $appRoute            
     */
    public static function getRoute($uri, array &$appRoute) {
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
    public static function urlrouter_rewrite(&$uri, $verb = null) {}
}
