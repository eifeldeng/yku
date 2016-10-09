<?php
class Model {
    CONST SLICE = 100;
    CONST HASH_TYPE_COMMON = "crc32";
    CONST HASH_TYPE_NATURAL = "";
    private static $redis_nodes = array ();
    protected  $redis_pools = array ();
    protected function get_node_redis($key, $module, $hash_type = "crc32") {
        $keyparts = explode ( ':', $key );
        $crcKey = array_pop ( $keyparts );
        
        if ($hash_type == self::HASH_TYPE_NATURAL) {
            $nodeIndex = (( int ) $crcKey) % self::SLICE;
        } else {
            $nodeIndex = abs ( crc32 ( $crcKey ) ) % self::SLICE;
        }
        unset ( $keyparts );
        $nodes = $this->get_redis_nodes ( $module );
        
        foreach ( $nodes as $node ) {
            $begin = $node ['bid'];
            $end = $node ['eid'];
            $node_id = $begin;
            if ($nodeIndex >= $begin && $nodeIndex <= $end) {
                $redis = $this->get_redis_pool ( $node );
                return $redis;
            }
        }
    }
    private function get_redis_nodes($module) {
        if (empty ( self::$redis_nodes [$module] )) {
            $this->set_redis_nodes ( $module );
        }
        
        return self::$redis_nodes [$module];
    }
    private function set_redis_nodes($module) {
        $redis_config = \Config::loadConfig ( 'redis', $module );
        self::$redis_nodes [$module] = $redis_config;
    }
    private function get_redis_pool($config) {
        $pool_index = $config ['ip'] . "_" . $config ['port'];
        if (! isset ( $this->redis_pools [$pool_index] )) {
            $this->set_redis_pool ( $config );
        }
        return $this->redis_pools [$pool_index];
    }
    private function set_redis_pool($config) {
        $pool_index = $config ['ip'] . "_" . $config ['port'];
        $redis = new \db\RedisSlice ( $config );
        $redis->connect ();
        $this->redis_pools [$pool_index] = $redis;
    }
}