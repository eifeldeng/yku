<?php



class TP_Sign{

    private static $_instance;
    private static $_tp_redis;
    private static $_tp_user_redis;


    public static function get_instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * redis连接
     * @throws Exception
     */
    public function redis_connect(){
        try {
            self::$_tp_redis = TP_Redis::get_instance()->connect('sign1');
        } catch (Exception $e) {//redis 连接失败
            throw new Exception(Config_Errmsg::ERR_REDIS_CONNECT, Config_Errno::ERR_REDIS_CONNECT);
        }
    }

    /**
     * 用户redis连接
     * @param $uid
     * @throws Exception
     */
    public function user_redis_connect($uid){
        try {
            if($uid % 10 < 5) {
                self::$_tp_user_redis = TP_Redis::get_instance()->connect('sign1');
            } else {
                self::$_tp_user_redis = TP_Redis::get_instance()->connect('sign2');
            }
        } catch (Exception $e) {//redis 连接失败
            throw new Exception(Config_Errmsg::ERR_REDIS_CONNECT, Config_Errno::ERR_REDIS_CONNECT);
        }
    }

    /**
     * 用户是否已签到
     * @param $uid
     * @return bool
     */
    public function user_is_sign($uid,$date){
        $key = "signv2:$uid";
        return self::$_tp_user_redis->zscore($key, $date) ? true : false;
    }


}