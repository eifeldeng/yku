<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2015/12/7
 * Time: 11:17
 */


class TP_Show{

    private static $_instance;
    private static $_redis;

    private static $_tv_config_key = "show2:tv:config";
    private static $_zy_config_key = "show2:zy:config";
    private static $_zpd_config_key = "show2:zpd:config";
    private static $_movie_config_key = "show2:movie:config";
    private static $_movie_homemade_config_key = "show2:movie:homemade:config";

    private static $_recommend_config_key = "show2:recommend:config";

    private static $_show_video_data_prefix_key = "show2:video:data:";
    private static $_show_video_list_key = "show2:video:list";



    private function __construct(){
        try{
            self::$_redis = TP_Redis::get_instance()->connect('activity');
        }catch (Exception $e) {//redis 连接失败
            throw new Exception(Config_Errmsg::ERR_REDIS_CONNECT, Config_Errno::ERR_REDIS_CONNECT);
        }
    }

    public static function get_instance(){
        if(!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 校验数组参数值是否为空
     * @param $arr
     * @return bool
     */
    public function verify_video_value($arr){
        foreach($arr as $key => $value){
            if(in_array($key,array('description'))){
                continue;
            }
            if(empty($value)){
                return false;
            }
        }
        return true;
    }

    /**
     * 设置推荐配置
     * @param $date
     * @param $config
     * @return int
     */
    public function set_recommend_config($date,$config){
        $key = self::$_recommend_config_key;
        return self::$_redis->hset($key,$date,json_encode($config));
    }

    /**
     * 删除推荐配置
     * @param $show_type
     * @param $show_id
     * @return bool|int
     */
    public function del_recommend_config($date){
        $key = self::$_recommend_config_key;
        return self::$_redis->hdel($key,$date);
    }

    /**
     * 获取推荐配置
     * @param $show_type
     * @return array
     */
    public function get_recommend_config_list(){
        $key = self::$_recommend_config_key;
        $config_list = self::$_redis->hgetall($key);
        foreach($config_list as $date => $config){
            $config_list[$date] = json_decode($config,true);
        }
        return $config_list;
    }



    /**
     * 获取节目配置key
     * @param $show_type
     * @return bool|string
     */
    public function get_show_config_key($show_type){
        if($show_type == 'tv'){
            $key = self::$_tv_config_key;
        }elseif($show_type == 'zy'){
            $key = self::$_zy_config_key;
        }elseif($show_type == 'zpd'){
            $key = self::$_zpd_config_key;
        }elseif($show_type == 'movie'){
            $key = self::$_movie_config_key;
        }elseif($show_type == 'movie_homemade'){
            $key = self::$_movie_homemade_config_key;
        }else{
            return false;
        }
        return $key;
    }

    /**
     * 设置节目配置
     * @param $show_type
     * @param $show_id
     * @param $config
     * @return int
     */
    public function set_show_config($show_type,$show_id,$config){
        $key = $this->get_show_config_key($show_type);
        return self::$_redis->hset($key,$show_id,json_encode($config));
    }

    /**
     * 删除节目配置
     * @param $show_type
     * @param $show_id
     * @return bool|int
     */
    public function del_show_config($show_type,$show_id){
        $key = $this->get_show_config_key($show_type);
        return self::$_redis->hdel($key,$show_id);
    }

    /**
     * 获取节目配置列表
     * @param $show_type
     * @return array
     */
    public function get_show_config_list($show_type){
        $key = $this->get_show_config_key($show_type);
        $config_list = self::$_redis->hgetall($key);
        foreach($config_list as $show_id => $config){
            $config_list[$show_id] = json_decode($config,true);
        }
        return $config_list;
    }

    /**
     * 添加到电视播放列表
     */
    public function set_tv_video_list($show_index_list,$prior){
        foreach($show_index_list as $date => $show_index){
            $video_list = $this->get_one_video_list($date);

            $tv_list = isset($video_list['tv_list']) ? $video_list['tv_list'] : array();
            if(!in_array($show_index,$tv_list)){
                array_push($tv_list,$show_index);
            }
            $video_list['tv_list'] = $tv_list;

            $prior_list = isset($video_list['prior_list']) ? $video_list['prior_list'] : array();
            $show_id = explode('_',$show_index)[0];
            $prior_list[$show_id] = $prior;
            $video_list['prior_list'] = $prior_list;

            $this->set_one_video_list($date,$video_list);
        }
        return true;
    }

    /**
     * 添加到综艺播放列表
     */
    public function set_zy_video_list($show_index_list,$prior){
        foreach($show_index_list as $date => $show_index){
            $video_list = $this->get_one_video_list($date);

            $zy_list = isset($video_list['zy_list']) ? $video_list['zy_list'] : array();
            if(!in_array($show_index,$zy_list)){
                array_push($zy_list,$show_index);
            }
            $video_list['zy_list'] = $zy_list;

            $prior_list = isset($video_list['prior_list']) ? $video_list['prior_list'] : array();
            $show_id = explode('_',$show_index)[0];
            $prior_list[$show_id] = $prior;
            $video_list['prior_list'] = $prior_list;

            $this->set_one_video_list($date,$video_list);
        }
        return true;
    }

    /**
     * 添加到自频道播放列表
     */
    public function set_zpd_video_list($show_index_list,$prior){
        foreach($show_index_list as $date => $show_index){
            $video_list = $this->get_one_video_list($date);

            $zpd_list = isset($video_list['zpd2_list']) ? $video_list['zpd2_list'] : array();
            if(!in_array($show_index,$zpd_list)){
                array_push($zpd_list,$show_index);
            }
            $video_list['zpd2_list'] = $zpd_list;

            $prior_list = isset($video_list['prior_list']) ? $video_list['prior_list'] : array();
            $show_id = explode('_',$show_index)[0];
            $prior_list[$show_id] = $prior;
            $video_list['prior_list'] = $prior_list;

            $this->set_one_video_list($date,$video_list);
        }
        return true;
    }

    /**
     * 添加到电影播放列表
     */
    public function set_movie_video_list($show_index_list,$prior){
        foreach($show_index_list as $date => $show_index){
            $video_list = $this->get_one_video_list($date);

            $movie_list = isset($video_list['movie_list']) ? $video_list['movie_list'] : array();
            if(!in_array($show_index,$movie_list)){
                array_push($movie_list,$show_index);
            }
            $video_list['movie_list'] = $movie_list;

            $prior_list = isset($video_list['prior_list']) ? $video_list['prior_list'] : array();
            $show_id = explode('_',$show_index)[0];
            $prior_list[$show_id] = $prior;
            $video_list['prior_list'] = $prior_list;

            $this->set_one_video_list($date,$video_list);
        }
        return true;
    }

    /**
     * 添加到电影自制播放列表
     */
    public function set_movie_homemade_video_list($show_index_list,$prior){
        foreach($show_index_list as $date => $show_index){
            $video_list = $this->get_one_video_list($date);

            $movie_homemade_list = isset($video_list['movie_homemade_list']) ? $video_list['movie_homemade_list'] : array();
            if(!in_array($show_index,$movie_homemade_list)){
                array_push($movie_homemade_list,$show_index);
            }
            $video_list['movie_homemade_list'] = $movie_homemade_list;

            $prior_list = isset($video_list['prior_list']) ? $video_list['prior_list'] : array();
            $show_id = explode('_',$show_index)[0];
            $prior_list[$show_id] = $prior;
            $video_list['prior_list'] = $prior_list;

            $this->set_one_video_list($date,$video_list);
        }
        return true;
    }

    /**
     * 获取某一天播放列表
     * @param $date
     * @return array|mixed|stdClass
     */
    public function get_one_video_list($date){
        $key = self::$_show_video_list_key;
        $video_list = self::$_redis->hget($key,$date);
        return json_decode($video_list,true);
    }

    /**
     * 设置单个播放列表
     * @param $video_list
     * @return bool
     */
    public function set_one_video_list($date,$video_list){
        return self::$_redis->hset(self::$_show_video_list_key,$date,json_encode($video_list));
    }

    /**
     * 获取播放列表
     */
    public function get_video_list($date_arr = array()){
        $key = self::$_show_video_list_key;
        if(empty($date_arr)){
            $video_list = self::$_redis->hgetall($key);
        }else{
            $video_list = self::$_redis->hmget($key,$date_arr);
        }
        if(!empty($video_list)){
            foreach($video_list as $date => $value){
                if(!empty($video_list[$date])){
                    $video_list[$date] = json_decode($value,true);
                }else{
                    unset($video_list[$date]);
                }
            }
        }
        return $video_list;
    }

    /**
     * 设置所有的播放列表
     * @param $video_list
     * @return bool
     */
    public function set_video_list($video_list){
        return self::$_redis->hmset(self::$_show_video_list_key,$video_list);
    }

    /**
     * 删除列表
     * @param $date
     * @return int
     */
    public function del_video_list($date){
        return self::$_redis->hdel(self::$_show_video_list_key,$date);
    }

    /**
     * 获取视频数据key
     * @param $show_id
     * @param $stage
     * @return string
     */
    public function get_video_data_key($video_index){
        return self::$_show_video_data_prefix_key.$video_index;
    }

    /**
     * 添加视频数据
     */
    public function set_video_data($video_data){
        if(empty($video_data) || !is_array($video_data)){
            return false;
        }

        $mset_arr =  array();
        foreach($video_data as $show_index => $video){
                $key = $this->get_video_data_key($show_index);
                $value = json_encode($video);
                $mset_arr[$key] = $value;
        }

        return self::$_redis->mset($mset_arr);
    }

    /**
     * 获取视频数据
     * @param $show_index_arr
     * @return array
     */
    public function get_video_data($show_index_arr){
        $data = array();

        $video_index_arr = array();
        foreach($show_index_arr as $show_index){
            $key = $this->get_video_data_key($show_index);
            array_push($video_index_arr,$key);
        }

        $video_data = self::$_redis->mget($video_index_arr);
        foreach($video_data as $k=>$item){
            $data[$show_index_arr[$k]] = json_decode($item,true);
        }

        return $data;
    }




    /**
     * 获取节目信息
     * @param $show_id_list
     * @return array|bool|mixed|stdClass
     */
    public function get_show_info($show_id_list){
        $show_id_arr = explode(',',$show_id_list);
        $show_id_count = count($show_id_arr);
        $url = 'http://10.103.88.54/show.show?q=showid:'.$show_id_list.'&fc=&pn=1&pl='.$show_id_count.'&ob=showweek_vv:desc&ft=json&cl=test_page&isnew=0';
        $rsp = TP_Curl::get($url);
        if(empty($rsp)) return false;

        $show_info_data = array();
        $rsp = json_decode($rsp,true);
        if(!empty($rsp['results'])){
            foreach($rsp['results'] as $result){
                $show_id = $result['pk_odshow'];
                $tmp_data = array(
                    'showid_en' => $result['showid'],
                    'showname' => $result['showname'],
                    'show_thumburl' => $result['show_thumburl'],
                    'show_vthumburl' => $result['show_vthumburl'],
                    'showtotal_vv' => $result['showtotal_vv'],
                );
                $show_info_data[$show_id] = $tmp_data;
            }
        }

        return $show_info_data;
    }

    /**
     * 获取视频信息
     * @param $show_id_en
     * @param int $count
     * @param string $orderby
     * @param string $show_videotype
     * @return array|bool|mixed|stdClass
     */
    public function get_video_info($show_id_en,$count = 20,$orderby = 'videoseq-desc',$show_videotype = '正片'){
        $url = "https://openapi.youku.com/v2/shows/videos.json?show_id=".$show_id_en."&client_id=e90f606722a38069&page=1&count=".$count."&orderby=".$orderby."&show_videotype=".$show_videotype;
        $rsp = TP_Curl::get($url);
        if(empty($rsp)) return false;
        return json_decode($rsp,true);
    }


}
