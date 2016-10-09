<?php

namespace YkuServer\Coroutine;

class RetVal {
    protected $info;
    public function __construct($info) {
        $this->info = $info;
    }
}