<?php
class AutoLoad {
    private static $root_path = array ();
    public static function auto_load($className) {
        $pathArr = array ();
        // root_path 为空，默认添加src目录
        if (empty ( self::$root_path )) {
            self::$root_path [] = dirname ( dirname ( __FILE__ ) );
        }
        foreach ( self::$root_path as $key => $root ) {
            $pathArr = array_merge ( $pathArr, self::set_include_path ( $root ) );
        }
        
        // 支持命名空间
        $className = strtr ( $className, '\\', DIRECTORY_SEPARATOR );
        foreach ( $pathArr as $key => $path ) {
            $class_file = $path . DIRECTORY_SEPARATOR . $className . ".php";
            if (is_file ( $class_file )) {
                include_once ($class_file);
                break;
            }
        }
    }
    
    /**
     * [setRoot 设置root根目录，可以同时添加多个]
     *
     * @param array $root
     *            [array]
     */
    public static function setRoot($rootArr = array()) {
        if (is_array ( $rootArr )) {
            self::$root_path = array_merge ( self::$root_path, $rootArr );
        }
    }
    public static function addRoot($root) {
        if (isset ( $root )) {
            self::$root_path [] = $root;
        }
    }
    public static function getFatherPath($path, $num = 1) {
        if (! isset ( $path )) {
            // 未设置路径，返回当前路径
            return dirname ( __FILE__ );
        }
        $needle = (PHP_OS == 'WINNT') ? '\\' : '/';
        for($i = 0; $i < $num; $i ++) {
            $path = substr ( $path, 0, strrpos ( $path, $needle ) );
        }
        return $path;
    }
    public static function loadThridParty($path) {
    }
    private static function set_include_path($dir) {
        $arr = scandir ( $dir );
        $include_paths = array ();
        for($i = 2; $i < count ( $arr ); $i ++) {
            if (is_dir ( $dir . DIRECTORY_SEPARATOR . $arr [$i] )) {
                $include_paths = array_merge ( $include_paths, self::set_include_path ( $dir . DIRECTORY_SEPARATOR . $arr [$i] ) );
            }
            $include_paths [] = $dir;
        }
        return array_unique ( $include_paths );
    }
}

spl_autoload_register ( array (
        'AutoLoad',
        'auto_load' 
) );

?>