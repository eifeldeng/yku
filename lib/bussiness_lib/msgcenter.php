<?php


class TP_MsgCenter {
    public static $_instance;
    public static function get_instance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function test() {
        echo 'Service_User<br/>';
    }
    function __construct() {
        $this->smiley_img = array(
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo1.gif" alt="沙发" title="沙发" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo2.gif" alt="难过" title="难过" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo3.gif" alt="害羞" title="害羞" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo4.gif" alt="愤怒" title="愤怒" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo5.gif" alt="无语" title="无语" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo6.gif" alt="哦" title="哦" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo7.gif" alt="稀饭" title="稀饭" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo8.gif" alt="酷" title="酷" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo9.gif" alt="鲜花" title="鲜花" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo10.gif" alt="晕" title="晕" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo11.gif" alt="汗" title="汗" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo12.gif" alt="搞笑" title="搞笑" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo13.gif" alt="调皮" title="调皮" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo14.gif" alt="吐" title="吐" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo15.gif" alt="疑问" title="疑问" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo16.gif" alt="亲嘴" title="亲嘴" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Yo17.gif" alt="赞" title="赞" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo18.gif" alt="顶" title="顶" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Qoo12.gif" alt="哈哈" title="哈哈" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Zoo7.gif" alt="牛" title="牛" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/smiley/Zoo8.gif" alt="强" title="强" />',
        );
        $this->smiley_img_wanwan = array(
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/bangjia.gif" alt="绑架" title="绑架" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/buzantong.gif" alt="不赞同" title="不赞同" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/dainu.gif" alt="呆怒" title="呆怒" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/daiyou.gif" alt="呆右" title="呆右" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/daizuo.gif" alt="呆左" title="呆左" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/daoyan.gif" alt="导演" title="导演" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/dishi.gif" alt="敌视" title="敌视" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/duzui.gif" alt="嘟嘴" title="嘟嘴" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/fuerdai.gif" alt="富二代" title="富二代" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/guzhang.gif" alt="鼓掌" title="鼓掌" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/jiaochatui.gif" alt="交叉腿" title="交叉腿" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/jiaochatuiyou.gif" alt="交叉腿右" title="交叉腿右" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/jiaochatuizuo.gif" alt="交叉腿左" title="交叉腿左" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/jiaoshou.gif" alt="叫兽" title="叫兽" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/koubi.gif" alt="抠鼻" title="抠鼻" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/kuqi.gif" alt="哭泣" title="哭泣" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/lalian.gif" alt="拉链" title="拉链" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/liubei.gif" alt="刘备" title="刘备" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/mei.gif" alt="没" title="没" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/niubi.gif" alt="牛逼" title="牛逼" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/shanjian.gif" alt="闪剑" title="闪剑" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/shuai.gif" alt="帅" title="帅" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/tiaomei.gif" alt="挑眉" title="挑眉" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/outu.gif" alt="呕吐" title="呕吐" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/tushetou.gif" alt="吐舌头" title="吐舌头" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/wukong.gif" alt="悟空" title="悟空" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/xiangjiao.gif" alt="香蕉" title="香蕉" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/xiao.gif" alt="笑" title="笑" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/yiwen.gif" alt="疑惑" title="疑惑" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/yuannian.gif" alt="怨念" title="怨念" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/dazan.gif" alt="大赞" title="大赞" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/zhuchiren.gif" alt="主持人" title="主持人" />',
            '<img src="http://static.youku.com/v1.0.1071/index/img/vip_emotion/wanwan/zijinbo.gif" alt="紫金钵" title="紫金钵" />',
        );
    }
    public $smiley_code = array(
        '[Yo1]',
        '[Yo2]',
        '[Yo3]',
        '[Qoo4]',
        '[Qoo5]',
        '[Qoo6]',
        '[Qoo7]',
        '[Qoo8]',
        '[Yo9]',
        '[Qoo10]',
        '[Qoo11]',
        '[Qoo12]',
        '[Qoo13]',
        '[Qoo14]',
        '[Qoo15]',
        '[Yo16]',
        '[Yo17]',
        '[Qoo18]',
        '[Zoo4]',
        '[Zoo7]',
        '[Zoo8]'
    );
    public $chinese_code = array(
        '[沙发]',
        '[难过]',
        '[害羞]',
        '[愤怒]',
        '[无语]',
        '[哦]',
        '[稀饭]',
        '[酷]',
        '[鲜花]',
        '[晕]',
        '[汗]',
        '[搞笑]',
        '[调皮]',
        '[吐]',
        '[疑问]',
        '[亲嘴]',
        '[赞]',
        '[顶]',
        '[哈哈]',
        '[牛]',
        '[强]'
    );
    public $chinese_code_wanwan = array(
        '[绑架]',
        '[不赞同]',
        '[呆怒]',
        '[呆右]',
        '[呆左]',
        '[导演]',
        '[敌视]',
        '[嘟嘴]',
        '[富二代]',
        '[鼓掌]',
        '[交叉腿]',
        '[交叉腿右]',
        '[交叉腿左]',
        '[叫兽]',
        '[抠鼻]',
        '[哭泣]',
        '[拉链]',
        '[刘备]',
        '[没]',
        '[牛逼]',
        '[闪剑]',
        '[帅]',
        '[挑眉]',
        '[呕吐]',
        '[吐舌头]',
        '[悟空]',
        '[香蕉]',
        '[笑]',
        '[疑惑]',
        '[怨念]',
        '[大赞]',
        '[主持人]',
        '[紫金钵]'
    );
    public function explainSmileyCode($str) {
        $str = TP_UBB::ubbCode($str);
        $str = str_replace($this->smiley_code, $this->smiley_img, $str);
        $str = str_replace($this->chinese_code, $this->smiley_img, $str);
        $str = str_replace($this->chinese_code_wanwan, $this->smiley_img_wanwan, $str);
        return $str;
    }
    /**
     * 获取$userId的粉丝列表
     *
     * @param int $userId
     * @param int $page_length
     * @return object
     */
    public function getFollowers($userId, $page=1,$page_length = 30) {
        try {
            $UC_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
            $url = 'http://' . $UC_API_SERVER . '/friendships/get_followers.json';
            $url.= '?uid=' . $userId . '&page='.$page.'&page_length=' . $page_length;
            $ret = json_decode(TP_Curl::get($url));
            return $ret;
        }
        catch(Exception $e) {
            return false;
        }
    }
    /**
     *获取新消息数量
     */
    public function getNoticeNum($userId) {
        $data = array(
            "followers" => 0,
            "favor_update" => 0,
            "private_msg" => 0,
            "comments" => 0,
            "act_notice" => 0,
            "renewal" => 0,
            "view_coupons" => 0,
            "grow_up" => 0,
            "video_publish" => 0,
            "self_channel" => 0,
        	"private_chat"=>0,
        	"level_up"=>0,
        	"task_credits"=>0,
        	"weiku_zhibo"=>0,
        	"bofangliang"=>0,
        	"crowdfunding"=>0,
        	"account_msg"=>0,
        );
        try {
            $_msgproxysvr = TP_Core::get_thrift('msgproxysvr');
            $ret_getunread = $_msgproxysvr->get_unread_msg($userId);
            if ($ret_getunread->ret == 0) {
                $msgunreadlist = $ret_getunread->msglist;
                foreach ($msgunreadlist as $key => $val) {
                    if (empty($val->msgcount)) continue;
                    if (in_array($val->msgtype_id, array(
                        7,
                        8,
                        9
                    ))) {
                        $data['comments']+= $val->msgcount;
                        continue;
                    }
                    switch ($val->msgtype_id) {
                        case 12:
                            $data['followers']+= $val->msgcount;
                            break;

                        case 6:
                            $data['private_msg']+= $val->msgcount;
                            break;

                        default:
                            break;
                    }
                }
            }
        }
        catch(Exception $e) {
        }
        try {
            $NOTIFY_API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('notifyAPIServers'));
            $request_url = 'http://' . $NOTIFY_API_SERVER . '/notify/get.json?uid=' . $userId . '&types=["mvip.msg.state"]';
            $result = json_decode(TP_Curl::get($request_url) , true);
            if (isset($result['notify']) && isset($result['notify']['mvip.msg.state'])) {
                $data['act_notice'] =isset($result['notify']['mvip.msg.state'][0]['2'])? $result['notify']['mvip.msg.state'][0]['2']:0;
                $data['renewal'] =isset($result['notify']['mvip.msg.state'][0]['3'])? $result['notify']['mvip.msg.state'][0]['3']:0;
                $data['view_coupons'] = isset($result['notify']['mvip.msg.state'][0]['1000']) ? $result['notify']['mvip.msg.state'][0]['1000'] : 0;
                $data['grow_up'] = isset($result['notify']['mvip.msg.state'][0]['1001']) ? $result['notify']['mvip.msg.state'][0]['1001'] : 0;
                $data['self_channel'] = isset($result['notify']['mvip.msg.state'][0]['1002']) ? $result['notify']['mvip.msg.state'][0]['1002'] : 0;
                $data['video_publish'] = isset($result['notify']['mvip.msg.state'][0]['1004']) ? $result['notify']['mvip.msg.state'][0]['1004'] : 0;
                $data['private_chat'] = isset($result['notify']['mvip.msg.state'][0]['1006']) ? $result['notify']['mvip.msg.state'][0]['1006'] : 0;
                if ($data['private_chat'] ==0) {
               		$data['private_chat'] = isset($result['notify']['mvip.msg.state'][0]['1007']) ? $result['notify']['mvip.msg.state'][0]['1007'] : 0;
                } 
                $data['level_up'] = isset($result['notify']['mvip.msg.state'][0]['1009']) ? $result['notify']['mvip.msg.state'][0]['1009'] : 0;
                $data['task_credits'] = isset($result['notify']['mvip.msg.state'][0]['1010']) ? $result['notify']['mvip.msg.state'][0]['1010'] : 0;
                $data['weiku_zhibo'] = isset($result['notify']['mvip.msg.state'][0]['1011']) ? $result['notify']['mvip.msg.state'][0]['1011'] : 0;
                $data['bofangliang'] = isset($result['notify']['mvip.msg.state'][0]['1012']) ? $result['notify']['mvip.msg.state'][0]['1012'] : 0;
                $data['crowdfunding'] = isset($result['notify']['mvip.msg.state'][0]['1013']) ? $result['notify']['mvip.msg.state'][0]['1013'] : 0;
                $data['account_msg'] = isset($result['notify']['mvip.msg.state'][0]['1014']) ? $result['notify']['mvip.msg.state'][0]['1014'] : 0;
                 
            }
        }
        catch(Exception $e) {
        }
        return $data;
    }
    /**
     * 重置消息数 -- 接入UMC接口
     * http://navi.1verge.net/project:msgcenter:api#msgcenter_read_confirm
     * @param int $uid
     * @param string $types
     * @return boolean
     */
    public function resetNoticeNumUMC($uid, $types) {
        if (!$uid || $uid <= 0 || !$types) {
            return false;
        }
        try {
            $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('msgcenterAPIServers'));
            $api = 'http://' . $API_SERVER . '/msgcenter/read_confirm.json?uid=' . $uid . '&terminal_id=1&msgtype_id=' . $types;
            $data = json_decode(TP_Curl::get($api) , true);
            if (isset($data['e']) && $data['e']['code'] === 0) {
                return true;
            }
        }
        catch(Exception $e) {
        }
        return false;
    }
    public function getCommentCount($uid) {
        $totalNum = array(
            "sent" => 0,
            "received" => 0
        );
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('commentAPIServers'));
        $api = 'http://' . $API_SERVER . '/user.comment/get_status.json?uid=' . $uid;
        $result = json_decode(TP_Curl::get($api) , true);
        if (isset($result['error']) && $result['error'] === 1) {
            if (isset($result['data']['self']) && $result['data']['self']['total'] > 0) {
                $totalNum['sent']+= $result['data']['self']['total'];
            }
            if (isset($result['data']['received']) && $result['data']['received']['total'] > 0) {
                $totalNum['received']+= $result['data']['received']['total'];
            }
        }
        return $totalNum;
    }
    /**
     *  获取用户发表的评论
     */
    public function getSentComments($uid, $page = 1, $page_length = 30) {
        if (empty($uid)) return null;
        $page = ($page > 0) ? $page - 1 : $page;
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('commentAPIServers'));
        $api = 'http://' . $API_SERVER . '/user.comment/get_self.json?uid=' . $uid . '&pg=' . $page . '&pl=' . $page_length;
        $result = json_decode(TP_Curl::get($api));
        if (isset($result->error) && $result->error === 1) {
            foreach ($result->data as $key => $value) {
                $value->decodeTxt = $this->explainSmileyCode($value->text);
                $value->text = htmlspecialchars($value->text);
                $value->create_at = date("Y-m-d H:i:s", $value->create_at);
                $value->create_at_txt = $this->getShortDate($value->create_at, 'v4');
                $videoInfo = $this->getVideosInfo($value->video->vid);
                if (!empty($videoInfo)) {
                    $videoInfo['snippet']['userIdEncode'] = TP_User::get_instance()->user_id_encode($videoInfo['snippet']['owner_id']);
                    $videoInfo['snippet']['title'] = htmlspecialchars($videoInfo['snippet']['title']);
                    $videoInfo['snippet']['description'] = htmlspecialchars($videoInfo['snippet']['description']);                
                    $value->video = $videoInfo;
                }
            }
            return $result->data;
        }
        return null;
    }
    /**
     *  获取用户发表的评论
     *  $uid 用户id
     *  $sendOrRecive 发送的评论，收到的评论
     *  $page 页码
     *  $page_length 页大小
     */
    public function getCommentsSimple($uid, $sendOrRecive = 'send', $page = 1, $page_length = 30){
        if (empty($uid))
            return null;

        $page = ($page > 0) ? $page - 1 : 0;
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('commentAPIServers'));
        $avatar = '';
        $userInfo = array();
        if($sendOrRecive == 'send'){
            $avatar = TP_User::get_instance()->getUserAvatar($uid);
            $userInfo = TP_User::get_instance()->get_user_info_by_uid($uid);
            $api = 'http://' . $API_SERVER . '/user.comment/get_self.json?uid=' . $uid . '&pg=' . $page . '&pl=' . $page_length;
        }
        else if($sendOrRecive == 'receive'){
            $api = 'http://' . $API_SERVER . '/user.comment/get_received.json?uid=' . $uid . '&pg=' . $page . '&pl=' . $page_length;
        } else {
            return null;
        }
        
        
        $videos = array();
        $data = array();

        $result = json_decode(TP_Curl::get($api));
        if (isset($result->error) && $result->error === 1) {
            foreach ($result->data as $key => $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['text'] = $this->explainSmileyCode($value->text);
                $item['create_at'] = $value->create_at;

                $item['video']['id'] = $value->video->vid;
                if(array_key_exists($value->video->vid,$videos)){
					$item ['video'] ['name'] = $videos [$value->video->vid] ['snippet'] ['title'];
					$item ['video'] ['encoded_id'] = $videos [$value->video->vid] ['encoded_id'];
					$item ['video'] ['is_self'] = ($uid == $videos [$value->video->vid] ['snippet'] ['owner_id']);
					$item ['to'] ['id'] = $videos [$value->video->vid] ['snippet'] ['owner_id'];
					$item ['to'] ['name'] = $videos [$value->video->vid] ['snippet'] ['owner_name'];
					$item ['to'] ['encoded_id'] = TP_User::get_instance ()->user_id_encode ( $item ['to'] ['id'] );
				}else{
                    $videoInfo = $this->getVideosInfo($value->video->vid);
                    if (!empty($videoInfo)) {
						$videos [$value->video->vid] = $videoInfo;
						$item ['video'] ['name'] = $videos [$value->video->vid] ['snippet'] ['title'];
						$item ['video'] ['encoded_id'] = $videos [$value->video->vid] ['encoded_id'];
						$item ['video'] ['is_self'] = ($uid == $videos [$value->video->vid] ['snippet'] ['owner_id']);
						$item ['to'] ['id'] = $videos [$value->video->vid] ['snippet'] ['owner_id'];
						$item ['to'] ['name'] = $videos [$value->video->vid] ['snippet'] ['owner_name'];
						$item ['to'] ['encoded_id'] = TP_User::get_instance ()->user_id_encode ( $item ['to'] ['id'] );
					}
                }

                if($sendOrRecive == 'receive'){
                    $item['from']['id'] = $value->poster->uid;
                    $item['from']['avatar'] = TP_User::get_instance()->getUserAvatar($value->poster->uid);
                    $item['from']['encoded_id'] = TP_User::get_instance()->user_id_encode($value->poster->uid);
                    $userInfo = TP_User::get_instance()->get_user_info_by_uid($value->poster->uid);
                    $item['from']['name'] = $userInfo['name'];
                    $item['uid'] = $uid;
                } else if($sendOrRecive == 'send'){
                    $item['from']['id'] = $uid;
                    $item['from']['avatar'] = $avatar;
                    $item['from']['encoded_id'] = TP_User::get_instance()->user_id_encode($uid);
                    $item['from']['name'] = $userInfo['name'];
                }

                $data[] = $item;
            }

            return $data;
        }
        return null;
    }
    /**
     *  获取用户收到的评论
     */
    public function getReceivedComments($uid, $page = 1, $page_length = 30) {
        if (empty($uid)) return null;
        $page = ($page > 0) ? $page - 1 : $page;
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('commentAPIServers'));
        $api = 'http://' . $API_SERVER . '/user.comment/get_received.json?uid=' . $uid . '&pg=' . $page . '&pl=' . $page_length;
        $result = json_decode(TP_Curl::get($api));
        if (isset($result->error) && $result->error === 1) {
            foreach ($result->data as $key => $value) {
                $value->decodeTxt = $this->explainSmileyCode($value->text);
                $value->text = htmlspecialchars($value->text);
                $value->create_at = date("Y-m-d H:i:s", $value->create_at);
                $value->create_at_txt = $this->getShortDate($value->create_at, 'v4');
                $videoInfo = $this->getVideosInfo($value->video->vid);
                if (!empty($videoInfo)&&isset($videoInfo['snippet'])) {
                    $videoInfo['snippet']['is_self'] = $uid == $videoInfo['snippet']['owner_id'];
                    $videoInfo['snippet']['userIdEncode'] = TP_User::get_instance()->user_id_encode($videoInfo['snippet']['owner_id']);
                    $videoInfo['snippet']['title'] = htmlspecialchars($videoInfo['snippet']['title']);
                    $videoInfo['snippet']['description'] = htmlspecialchars($videoInfo['snippet']['description']);
                    $value->video = $videoInfo;
                }
            }
            return $result->data;
        }
        return null;
    }
    /**
     * 获取视频信息
     */
    public function getVideosInfo($vids) {
        $ret = array();
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('folderListAPIServers'));
        $api = 'http://' . $API_SERVER . '/videos/show.json?id=' . $vids . '&caller=UCENTER';
        $ret = json_decode(TP_Curl::get($api) , true);
        if (isset($ret['data']) && isset($ret['data'][0])) {
            $ret = $ret['data'][0];
        }
        return $ret;
    }
    /**
     * 与所有会话人的私信列表.
     *
     * @param array $params
     */
    public function getPrivateMsgList($uid, $page = 1, $page_length = 30,$from='web') {
        $renderPageData = array(
            'total_msg' => 0
        );
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
        $api = 'http://' . $API_SERVER . '/users/message/get_conversationers.json?uid=' . $uid . '&page=' . $page . '&page_length=' . $page_length;
        $privateMsg = json_decode(TP_Curl::get($api));
        if (empty($privateMsg) || !is_object($privateMsg)) return $renderPageData;
        $renderPageData['curr_page'] = $page;
        $renderPageData['page_step'] = $page_length;
        $renderPageData['total_msg'] = $privateMsg->total;
        $renderPageData['total_pages'] = ceil($privateMsg->total / $page_length);

        $action_name = array('web' =>'private_msg' , 'pc' =>'pc_private_msg');
        foreach ($privateMsg->conversationers as $msg) {
            if (empty($msg->last_message)) continue;
            $msgState = ($msg->last_message->sender->uid == $msg->uid) ? 0 : 1;
            $userInfo = $msgState == 1 ? $msg->last_message->receiver : $msg->last_message->sender;
            //获取用户详细信息
            $userDetail = TP_User::get_instance()->get_user_info_by_uid($userInfo->uid);
            $user_verified = isset($userDetail['verified']) ? $userDetail['verified'] : 0;
            $renderPageData['data'][] = array(
                'uid' => $uid,
                'encode_uid' => TP_User::get_instance()->user_id_encode($uid) ,
                'encode_cuid' => TP_User::get_instance()->user_id_encode($msg->uid) ,
                'nick_name' => htmlspecialchars($msg->nick_name) ,
                'avater' => $userDetail['profile_image_url']['big'],
                'user_verified' => $user_verified,
                'msg_state' => $msgState, // 对于列表中的用户，最后一条消息的状态：0:发出；1:收到
                'content' => $this->_formatLinkToHtml($this->_substr($msg->last_message->content, 300)) , //连接处理
                'time' => date("Y-m-d H:i:s", $msg->last_message->creat_time) ,
                'format_time' => $this->getShortDate(date("Y-m-d H:i:s", $msg->last_message->creat_time) , 'v4') ,
                'msg_num' => $msg->total_message,
                'unread' => isset($msg->unread) ? $msg->unread : 0,
                'pmsg_link' => '/page/msg/'.$action_name[$from].'?cuid=' . TP_User::get_instance()->user_id_encode($msg->uid) ,
                'jira_id' => !empty($msg->last_message->jira_id) ? $msg->last_message->jira_id : ''
            );
        }
        return $renderPageData;
    }
    /**
     * 与某人的私信通话的内容.
     *
     * @param array $params
     */
    public function getPrivateMsgOner($userId, $cuid, $currPage = 1, $pageMsgNum = 30) {
        $renderPageData = array(
            'code' => 1,
            'total_msg' => 0
        );
        if (!is_numeric(TP_User::get_instance()->decode($cuid))) {
            $renderPageData['code'] = - 1002;
        }
        if ($renderPageData['code'] < 0) {
            return $renderPageData;
        }
        $API_SERVER = TP_Helper::array_random_element(TP_Config::get_msgcenter('ucenterAPIServers'));
        $api = 'http://' . $API_SERVER . '/users/message/get_conversation.json?uid=' . $userId . '&conversationer=' . TP_User::get_instance()->decode($cuid) . '&page=' . $currPage . '&page_length=' . $pageMsgNum;
        $privateMsg = json_decode(TP_Curl::get($api));
        if (empty($privateMsg) || !is_object($privateMsg)) {
            $renderPageData['code'] = - 1100;
            return $renderPageData;
        }
        $renderPageData['curr_page'] = $currPage;
        $renderPageData['page_step'] = $pageMsgNum;
        $renderPageData['total_msg'] = $privateMsg->total;
        $renderPageData['unread'] = $privateMsg->unread;
        $renderPageData['data'] = array();
        $renderPageData['my_info'] = $renderPageData['his_info'] = array();
        $renderPageData['default_jira'] = null;
        $gid = 0;
        foreach ($privateMsg->messages as $msg) {
            $isme = $msg->sender->uid == $userId ? 1 : 0;
            $tempBaseData = array(
                'uid' => $msg->sender->uid,
                'isme' => $isme,
                'count' => 1,
            );
            $tempMsgData = array(
                'msgid' => $msg->id,
                'content' => $this->_formatLinkToHtml($msg->content) , //连接处理
                'time' => date('YmdHis', $msg->creat_time) ,
                'jira_id' => !empty($msg->jira_id) ? $msg->jira_id : '',
            );
            if (is_null($renderPageData['default_jira'])) { //默认取最近私信jiraid
                $renderPageData['default_jira'] = !empty($msg->jira_id) ? $msg->jira_id : '';
            }
            //获取用户详细信息
            $userDetail = TP_User::get_instance()->get_user_info_by_uid($msg->sender->uid);
            $user_verified = isset($userDetail['verified']) ? $userDetail['verified'] : 0;
            $tempMsgData['user_verified'] = $user_verified;
            if ($gid > 0) {
                if ($renderPageData['data'][$gid - 1]['base_data']['uid'] == $msg->sender->uid && substr($renderPageData['data'][$gid - 1]['msg_data'][0]['time'], 0, 8) == substr($tempMsgData['time'], 0, 8)) {
                    $renderPageData['data'][$gid - 1]['base_data']['count']++;
                    $renderPageData['data'][$gid - 1]['msg_data'][] = $tempMsgData;
                } else {
                    $renderPageData['data'][$gid]['base_data'] = $tempBaseData;
                    $renderPageData['data'][$gid]['msg_data'][] = $tempMsgData;
                    $gid++;
                }
            } else {
                $renderPageData['data'][$gid]['base_data'] = $tempBaseData;
                $renderPageData['data'][$gid]['msg_data'][] = $tempMsgData;
                $gid++;
                // 初始化对话双方个人信息
                $converationer1 = array(
                    'uid' => $msg->sender->uid,
                    'encode_uid' => TP_User::get_instance()->user_id_encode($msg->sender->uid) ,
                    'nick_name' => htmlspecialchars($msg->sender->nick_name) ,
                    'avater' => $this->_getUserIcon($msg->sender) ,
                );
                $converationer2 = array(
                    'uid' => $msg->receiver->uid,
                    'encode_uid' => TP_User::get_instance()->user_id_encode($msg->receiver->uid) ,
                    'nick_name' => htmlspecialchars($msg->receiver->nick_name) ,
                    'avater' => $this->_getUserIcon($msg->receiver) ,
                );
                if ($userId == $msg->sender->uid) {
                    $renderPageData['my_info'] = $converationer1;
                    $renderPageData['his_info'] = $converationer2;
                    $renderPageData['owner_latest_msg'] = '1_' . $tempMsgData['time'];
                } else {
                    $renderPageData['my_info'] = $converationer2;
                    $renderPageData['his_info'] = $converationer1;
                    $renderPageData['owner_latest_msg'] = '0';
                }
            }
        }
        return $renderPageData;
    }
    /**
     * 获取用户的头像.
     *
     * @param array $userinfo
     */
    private function _getUserIcon($userinfo) {
        if (!empty($userinfo->avator_small)) {
            $userIcon = $userinfo->avator_small;
        } else {
            $avatar = TP_User::get_instance()->getUserIconUrl($userinfo->uid);
            $userIcon = $avatar->small;
        }
        return $userIcon;
    }
    /**
     * 字符串截取 - 如果字符串中包含连接，保持完整连接.
     *
     * @param type $str
     * @param type $substrLen
     */
    private function _substr($str, $substrLen) {
        $strLen = mb_strlen($str, 'UTF-8');
        $showStr = '';
        if ($strLen <= $substrLen) {
            $showStr = $str;
        } else {
            $pattern = '#(https?:\/\/[\w|\d|:|\.|\/|\#|\=|\&|\;|\%|\?|\-|\_|\+|\*|\@]+)#';
            $links = array();
            preg_match_all($pattern, $str, $links);
            if (empty($links[0])) {
                $showStr = mb_substr($str, 0, $substrLen, 'UTF-8');
                $showStr.= ' ...';
            } else {
                $curstrLen = 0;
                $strArray = preg_split($pattern, $str);
                for ($i = 0, $count = count($strArray); $i < $count; $i++) {
                    $strValue = $strArray[$i];
                    $strValueLen = mb_strlen($strValue, 'UTF-8');
                    if ($curstrLen >= $substrLen) {
                        break;
                    } else if ($curstrLen + $strValueLen >= $substrLen) {
                        $showStr.= mb_substr($strValue, 0, $substrLen - $curstrLen, 'UTF-8');
                        break;
                    } else {
                        $showStr.= $strValue;
                        $curstrLen+= $strValueLen;
                        if (isset($links[0][$i])) {
                            $showStr.= $links[0][$i];
                            $curstrLen+= mb_strlen($links[0][$i], "UTF-8");
                        }
                    }
                } //for
                $showStr.= ' ...';
            } //if
            
        } //if
        return $showStr;
    }
    /**
     * 为内容中的连接添加a标签.
     *
     * @param string $str
     */
    private function _formatLinkToHtml($str) {
        $pattern = '#(https?:\/\/[\w|\d|:|\.|\/|\#|\=|\&|\;|\%|\?|\-|\_|\+|\*|\@]+)#';
        $replace = '<a href="#" data-link="$1" target="_blank" class="contentLink">$1</a>';
        return !empty($str) ? preg_replace($pattern, $replace, htmlspecialchars($str)) : '';
    }
    public function getShortDate($string, $ver = 'v1') {
        if (empty($string)) {
            $last_login_date_str = "未知";
        } else {
            if ($ver == 'v1') {
                $last_login_date_str = ((strtotime("now") - strtotime($string)) / 60);
                if ($last_login_date_str < 60) {
                    if ($last_login_date_str < 0) $last_login_date_str = 0;
                    $last_login_date_str = ((int)$last_login_date_str) . "分钟前";
                } else if ($last_login_date_str < 60 * 24) {
                    $last_login_date_str = ((int)($last_login_date_str / 60)) . "小时前";
                } else if ($last_login_date_str < 60 * 24 * 30) {
                    $last_login_date_str = ((int)($last_login_date_str / (60 * 24))) . "天前";
                } else if ($last_login_date_str < 60 * 24 * 365) {
                    $last_login_date_str = ((int)($last_login_date_str / (60 * 24 * 30))) . "个月前";
                } else {
                    $last_login_date_str = ((int)($last_login_date_str / (60 * 24 * 365))) . "年前";
                }
            }
            if ($ver == 'v2') {
                if (strtotime("now") - strtotime($string) > 86400 * 365) { //超过一年的显示年月日
                    $last_login_date_str = strftime("%Y-%m-%d", strtotime($string));
                } elseif (strtotime($string) - strtotime(date("Ymd", time())) < 0) { //超过零时显示月日
                    $last_login_date_str = strftime("%m-%d", strtotime($string));
                } else {
                    $last_login_date_str = strftime("%H:%M", strtotime($string));
                }
            }
            if ($ver == 'v3') {
                $last_login_date_str = ((strtotime("now") - strtotime($string)) / 60);
                if ($last_login_date_str < 60) {
                    if ($last_login_date_str < 0) $last_login_date_str = 0;
                    $last_login_date_str = "(" . ((int)$last_login_date_str) . "分钟)";
                } else if ($last_login_date_str < 60 * 24) {
                    $last_login_date_str = "(" . ((int)($last_login_date_str / 60)) . "小时)";
                } else if ($last_login_date_str < 60 * 24 * 30) {
                    $last_login_date_str = ((int)($last_login_date_str / (60 * 24))) < 31 ? "(" . ((int)($last_login_date_str / (60 * 24))) . "天)" : "";
                } else {
                    $last_login_date_str = "";
                }
            }
            if ($ver == 'v4') { // 用户空间状态时间格式 liju 2011-08-30
                //需要过滤 20150122 和  20150122194451
                if (is_numeric($string) && (strlen($string) > 8) && (strlen($string) < 14)) {
                    $string = date("Y-m-d H:i:s", intval($string));
                }
                $last_login_date_str = ((strtotime("now") - strtotime($string)));
                $t0 = strtotime(strftime("%Y-%m-%d", strtotime("now")));
                $t1 = strtotime($string);
                //1分钟内
                if ($last_login_date_str < 60) {
                    $last_login_date_str = "刚刚";
                } //1小时内
                else if ($last_login_date_str < 3600) {
                    $last_login_date_str = (floor($last_login_date_str / 60)) . "分钟前";
                }
                //当天内
                else if ($t1 > $t0) {
                    $last_login_date_str = (floor($last_login_date_str / 3600)) . "小时前";
                }
                //昨天
                else if ($t0 - $t1 < 24 * 3600) {
                    $last_login_date_str = "昨天 " . strftime("%H:%M", strtotime($string));
                }
                //前天
                else if ($t0 - $t1 < 2 * 24 * 3600) {
                    $last_login_date_str = "前天 " . strftime("%H:%M", strtotime($string));
                }
                //一周内
                else if ($last_login_date_str < 7 * 24 * 3600) {
                    $last_login_date_str = floor($last_login_date_str / (24 * 3600)) . "天前";
                } else {
                    //本年内 || 去年但是当前是1月份
                    $year_now = (int)strftime("%Y", strtotime("now"));
                    $year_date = (int)strftime("%Y", strtotime($string));
                    $month_now = (int)strftime("%m", strtotime("now"));
                    if ($year_now == $year_date || ($year_now - $year_date == 1 && $month_now == 1)) {
                        $last_login_date_str = strftime("%m-%d %H:%M", strtotime($string));
                    }
                    //去年但当前是2月份及以后
                    else {
                        $last_login_date_str = strftime("%Y-%m-%d", strtotime($string));
                    }
                }
            }
            if ($ver == 'v5') { // 用户空间状态时间格式 Eric Pei 2013-01-18
                $last_login_date_str = ((strtotime("now") - strtotime($string)));
                $t0 = strtotime(strftime("%Y-%m-%d", strtotime("now")));
                $t1 = strtotime($string);
                //1分钟内
                if ($last_login_date_str < 60) {
                    $last_login_date_str = "刚刚";
                }
                //1小时内
                else if ($last_login_date_str < 3600) {
                    $last_login_date_str = (floor($last_login_date_str / 60)) . "分钟前";
                }
                //当天内
                else if ($t1 > $t0) {
                    $last_login_date_str = (floor($last_login_date_str / 3600)) . "小时前";
                }
                //昨天
                else if ($t0 - $t1 < 24 * 3600) {
                    $last_login_date_str = "昨天";
                }
                //前天
                else if ($t0 - $t1 < 2 * 24 * 3600) {
                    $last_login_date_str = "前天";
                }
                //一周内
                else if ($last_login_date_str < 7 * 24 * 3600) {
                    $last_login_date_str = floor($last_login_date_str / (24 * 3600)) . "天前";
                } else {
                    //本年内 || 去年但是当前是1月份
                    $year_now = (int)strftime("%Y", strtotime("now"));
                    $year_date = (int)strftime("%Y", strtotime($string));
                    $month_now = (int)strftime("%m", strtotime("now"));
                    if ($year_now == $year_date || ($year_now - $year_date == 1 && $month_now == 1)) {
                        $last_login_date_str = strftime("%m-%d", strtotime($string));
                    }
                    //去年但当前是2月份及以后
                    else {
                        $last_login_date_str = strftime("%Y", strtotime($string));
                    }
                }
            }
        }
        return $last_login_date_str;
    }
}

