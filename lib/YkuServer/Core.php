<?php

namespace YkuServer;

class Core {
    public static function error_handler($errno, $errstr, $errfile, $errline) {
        $errinfo = "[ $errno ] $errstr on line $errline in file $errfile";
        \SysLog::error ( 'error_handler', $errinfo );
    }
}