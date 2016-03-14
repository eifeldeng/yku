<?php
class ENVConst {
    const expire_time = 300; // 有效期
    const NUM = 684767; // 常量
    public static function getDBConf() {
        return array (
                'ip' => '0.0.0.0',
                'port' => '555' 
        );
    }
} 