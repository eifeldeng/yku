<?php
class TP_Filter {
    
    /**
     * 安全过滤类-获取GET或者POST的参数值，经过过滤
     * 如果不指定$type类型，则获取同名的，POST优先
     * $isfilter 默认开启，强制转换请求的数据
     * 该方法在Controller层中，获取所有GET或者POST数据，都需要走这个接口
     */
    
    /**
     * 安全过滤类-加反斜杠，放置SQL注入
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function filter_slashes(&$value) {
        if (get_magic_quotes_gpc ())
            return false; // 开启魔术变量
        $value = ( array ) $value;
        foreach ( $value as $key => $val ) {
            if (is_array ( $val )) {
                self::filter_slashes ( $value [$key] );
            } else {
                $value [$key] = urldecode ( addslashes ( $val ) );
            }
        }
    }
    
    /**
     * 安全过滤类-过滤javascript,css,iframes,object等不安全参数 过滤级别高
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function filter_script($value) {
        $value = preg_replace ( "/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i", "&111n\\2", $value );
        $value = preg_replace ( "/<script(.*?)>(.*?)<\/script>/si", "", $value );
        $value = preg_replace ( "/<iframe(.*?)>(.*?)<\/iframe>/si", "", $value );
        return $value;
    }
    
    /**
     * 安全过滤类-过滤HTML标签
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function filter_html($value) {
        if (function_exists ( 'htmlspecialchars' ))
            return htmlspecialchars ( $value );
        return str_replace ( array (
                "&",
                '"',
                "'",
                "<",
                ">" 
        ), array (
                "&amp;",
                "&quot;",
                "&#039;",
                "&lt;",
                "&gt;" 
        ), $value );
    }
    
    /**
     * 安全过滤类-对进入的数据加下划线 防止SQL注入
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function filter_sql($value) {
        $sql = array (
                "select",
                'insert',
                "update",
                "delete",
                "\'",
                "\/\*",
                "\.\.\/",
                "\.\/",
                "union",
                "into",
                "load_file",
                "outfile" 
        );
        $sql_re = array (
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "",
                "" 
        );
        return str_replace ( $sql, $sql_re, $value );
    }
    
    /**
     * 安全过滤类-通用数据过滤
     *
     * @param string $value
     *            需要过滤的变量
     * @return string|array
     */
    public static function filter_escape(&$value) {
        if (is_array ( $value )) {
            foreach ( $value as $k => $v ) {
                $value [$k] = self::filter_str ( $v );
            }
        } else {
            $value = self::filter_str ( $value );
        }
        return $value;
    }
    
    /**
     * 安全过滤类-字符串过滤 过滤特殊有危害字符
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function filter_str($value) {
        $value = htmlspecialchars ( $value );
        return $value;
    }
    
    /**
     * 私有路径安全转化
     *
     * @param string $fileName            
     * @return string
     */
    public static function filter_dir($fileName) {
        $tmpname = strtolower ( $fileName );
        $temp = array (
                '://',
                "\0",
                ".." 
        );
        if (str_replace ( $temp, '', $tmpname ) !== $tmpname) {
            return false;
        }
        return $fileName;
    }
    
    /**
     * 过滤目录
     *
     * @param string $path            
     * @return array
     */
    public static function filter_path($path) {
        $path = str_replace ( array (
                "'",
                '#',
                '=',
                '`',
                '$',
                '%',
                '&',
                ';' 
        ), '', $path );
        return rtrim ( preg_replace ( '/(\/){2,}|(\\\){1,}/', '/', $path ), '/' );
    }
    
    /**
     * 过滤PHP标签
     *
     * @param string $string            
     * @return string
     */
    public static function filter_phptag($string) {
        return str_replace ( array (
                '<?',
                '?>' 
        ), array (
                '&lt;?',
                '?&gt;' 
        ), $string );
    }
    
    /**
     * 安全过滤类-返回函数
     *
     * @param string $value
     *            需要过滤的值
     * @return string
     */
    public static function str_out($value) {
        $badstr = array (
                "&",
                '"',
                "'",
                "<",
                ">",
                "%3C",
                "%3E" 
        );
        $newstr = array (
                "&amp;",
                "&quot;",
                "&#039;",
                "&lt;",
                "&gt;",
                "&lt;",
                "&gt;" 
        );
        $value = str_replace ( $newstr, $badstr, $value );
        return stripslashes ( $value ); // 下划线
    }
    
    // 前面filter
    static public function preFilter($data) {
        return true;
    }
    
    // 后面filter
    static public function postFilter($data) {
    }
}