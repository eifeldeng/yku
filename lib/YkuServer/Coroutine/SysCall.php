<?php

namespace YkuServer\Coroutine;

class SysCall {
    public static function end($words = "") {
        return new RetVal ( $words );
    }
}