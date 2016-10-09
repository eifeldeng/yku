<?php

namespace YkuServer;

class Loader {
    /**
     * 命名空间的路径
     */
    static $nsPath;
    function __construct($swoole) {
    }
    
    /**
     * 自动载入类
     *
     * @param
     *            $class
     */
    static function autoload($class) {
        $root = explode ( '\\', trim ( $class, '\\' ), 2 );
        if (count ( $root ) > 1 and isset ( self::$nsPath [$root [0]] )) {
            include_once self::$nsPath [$root [0]] . '/' . str_replace ( '\\', '/', $root [1] ) . '.php';
        }
    }
    static function setRootNS($root, $path) {
        self::$nsPath [$root] = $path;
    }
}