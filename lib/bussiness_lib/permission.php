<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2015/8/12
 * Time: 15:32
 */

class TP_Permission{


    static $_instance = null;
    static $_db = null;

    static $_ldap_host = '10.10.0.20';
    static $_ldap_port = '389';

    static $_vipdata_cooike_key = 'vipdata_sign';
    static $_secret_key = 'e7b9c3263112aafc198522f66c60a094';

    static $_tbl_user = 'tbl_user';
    static $_tbl_role = 'tbl_role';
    static $_tbl_permission = 'tbl_priv';
    static $_tbl_operation_log = 'tbl_operation_log';

    private function __construct(){
        self::$_db = TP_Mysql::get_instance('vipdata');
    }

    public static function get_instance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 验证ldap账号
     * @param $user_name
     * @param $passwd
     * @return bool
     */
    public function account_verify($user_name,$passwd){
        $ldap_host = "ldap://".self::$_ldap_host;
        $ldap_port = self::$_ldap_port;//LDAP 服务器端口号
        $ldap_user = "$user_name@1verge.com";//设定服务器用户名
        $ldap_pwd = $passwd;//设定服务器密码
        $ldap_conn = ldap_connect($ldap_host, $ldap_port);//建立与 LDAP 服务器的连接
        $bind = ldap_bind($ldap_conn, $ldap_user, $ldap_pwd);//与服务器绑定
        ldap_unbind($ldap_conn);
        return $bind;
    }

    /**
     * 判断用户是否登陆
     * @return bool|string
     */
    public function get_login_status(){
        $cookie = $this->get_vipdata_cookie();
        if(empty($cookie)) return false;

        $verify = $this->verify_vipdata_cooike($cookie);
        if(!$verify){
            return false;
        }

        return true;
    }

    public function check_login_status(){
        if($this->get_login_status()==false){
            $view = TP_Core::init_view();
            $view->display('permission/login');
            exit();
        }
    }

    /**
     * 判断用户是否具有访问权限
     * @param $user_name
     * @param $priv_id
     * @return bool
     */
    public function get_access_permission($user_name,$priv_id){
        $user_info = $this->get_user($user_name);
        if(empty($user_info['user_role_id'])) return false;
        $role_id = $user_info['user_role_id'];
        $role_info = $this->get_role($role_id);
        if(empty($role_info['priv_ids'])) return false;
        $permission_info = $this->get_permission($priv_id);
        if(empty($permission_info) || $permission_info['status']=='0') return true;
        $priv_ids = explode(',',$role_info['priv_ids']);
        if(!in_array($priv_id,$priv_ids)){
            return false;
        }
        return true;
    }

    /**
     * 设置cookie值
     * @param $user_name
     * @return bool
     */
    public function set_vipdata_cookie($user_name,$nickname=''){
        $name = empty($nickname) ? $user_name : $nickname;
        $vipdata_cooike_key = self::$_vipdata_cooike_key;
        $vipdata_cooike_val = $this->gen_vipdata_cookie($user_name);
         setcookie('vipdata_username', $name,time()+86400,'/','youku.com');
        setcookie($vipdata_cooike_key,$vipdata_cooike_val,0,'/','youku.com');
        return true;
    }


    public function gen_vipdata_cookie($user_name){
        $timestamp = time();
        $sign = md5($user_name.$timestamp.self::$_secret_key);
        $val = base64_encode("{$user_name}|{$timestamp}|{$sign}");
        return $val;
    }

    /**
        * 校验用户访问权限
        * @return 如无权限直接显示提示页面，否则啥也不干
    */
    public function check_access_permission($permission_id,$title='',$ajax=false){
        if($this->get_access_permission($this->get_user_name_by_cookie(),$permission_id)==false){
            $view = TP_Core::init_view();
            if($ajax==false){
                $view->assign('title',$title);
                $view->assign('template','nopermission');
                $view->display('permission/common');
            }else{
                $view->display('permission/page_nopermission');
            }
            exit();
        }
    }

    /**
     * 获取cookie
     * @return bool|string
     */
    public function get_vipdata_cookie(){
        $key = self::$_vipdata_cooike_key;
        $val = isset($_COOKIE[$key]) ? base64_decode($_COOKIE[$key]) : '';
        return $val;
    }

    /**
     * 验证cookie值
     * @param $cookie
     * @return bool
     */
    public function verify_vipdata_cooike($cookie){
        $val = explode('|',$cookie);
        $user_name = isset($val[0]) ? $val[0] : '';
        $timestamp = isset($val[1]) ? $val[1] : '';
        $sign = isset($val[2]) ? $val[2] : '';

        $server_sign_str = $user_name.$timestamp.self::$_secret_key;
        $server_sign = md5($server_sign_str);
        if($server_sign != $sign){
            return false;
        }

        return true;
    }

    /**
     * 通过cooike获取user_name
     * @param $cookie
     * @return string
     */
    public function get_user_name_by_cookie(){
        $cookie = $this->get_vipdata_cookie();
        $val = explode('|',$cookie);
        $user_name = isset($val[0]) ? $val[0] : '';
        return $user_name;
    }

    /**
     * 插入用户信息
     * @param $user_name
     * @param string $user_nickname
     * @return int
     */
    public function insert_user($user_name,$user_nickname='',$user_role_id=0,$status=1){
        $table = self::$_tbl_user;
        $data = array(
            'user_name' => $user_name,
            'user_nickname' => $user_nickname,
            'user_role_id' => $user_role_id,
            'update_time' => time(),
            'ip' => TP_Helper::get_ip(),
            'status' => $status
        );
        $ret = self::$_db->insert($table,$data);

        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'insert');
        }
        return $ret;
    }

    /**
     * 更新用户最后登录时间
     * @param $user_name
     * @return int
     */
    public function update_user($user_name,$data){
        $table = self::$_tbl_user;        
        $cond = array('user_name'=>$user_name);
        $ret = self::$_db->update($table,$data,$cond);

        $name = $this->get_user_name_by_cookie();
        $name = empty($name) ? $user_name : $name;
        if($ret > 0){
            $this->record_operation_log($name,'update');
        }
        return $ret;
    }

    /**
     * 删除用户信息
     * @param $user_name
     * @return array|bool
     */
    public function delete_user($user_name){

        $table = self::$_tbl_user;
        $cond = array('user_name'=>$user_name);
        $ret = self::$_db->delete($table,$cond);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'delete');
        }
        return $ret;
    }




    /**
     * 查询用户信息
     * @param $user_name
     * @return array|bool
     */
    public function get_user($user_name){
        $table = self::$_tbl_user;
        $cond = array('user_name'=>$user_name);
        $ret = self::$_db->get($table,'*',$cond);
        return $ret;
    }

    /**
     * 分页批量获取用户信息
     * @param int $page
     * @param int $pagesize
     * @return array|bool
     */
    public function batch_get_user($page = 1,$pagesize = 20){
        $table = self::$_tbl_user;
        $other = " order by id asc  limit ".($page-1)*$pagesize.",".$pagesize;
        $ret = self::$_db->select($table,"*",array(),$other);
        return $ret;
    }

    /**
     * 插入权限
     * @param $priv_name
     * @return int
     */
    public function insert_permission($priv_name,$status){
        $table = self::$_tbl_permission;
        $data = array(
            'priv_name' => $priv_name,
            'status'=>$status
        );
        $ret = self::$_db->insert($table,$data);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'insert');
        }
        return $ret;
    }

    /**
     * 修改权限名称
     * @param $priv_id
     * @param $priv_name
     * @return int
     */
    public function update_permission($priv_id,$data){
        $table = self::$_tbl_permission;
        
        $cond = array('priv_id'=>$priv_id);
        $ret = self::$_db->update($table,$data,$cond);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'update');
        }
        return $ret;
    }

    /**
     * 删除权限
     * @param $priv_id
     * @return int
     */
    public function delete_permission($priv_id){
        $table = self::$_tbl_permission;
        $cond = array('priv_id'=>$priv_id);
        $ret = self::$_db->delete($table,$cond);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'delete');
        }
        return $ret;
    }

    /**
     * 获取权限
     * @param $priv_id
     * @return array|bool
     */
    public function get_permission($priv_id){
        $table = self::$_tbl_permission;
        $cond = array('priv_id'=>$priv_id);
        $ret = self::$_db->get($table,'*',$cond);
        return $ret;
    }


    /**
     * 分页批量获取权限信息
     * @param int $page
     * @param int $pagesize
     * @return array|bool
     */
    public function batch_get_permission($page = 1,$pagesize = 20){
        $table = self::$_tbl_permission;
        $other = " order by priv_id asc  limit ".($page-1)*$pagesize.",".$pagesize;
        $ret = self::$_db->select($table,"*",array(),$other);
        return $ret;
    }

    /**
     * 插入角色信息
     * @param $role_name
     * @param $priv_ids
     * @return int
     */
    public function insert_role($role_name,$priv_ids){
        $table = self::$_tbl_role;
        $data = array(
            'role_name' => $role_name,
            'priv_ids' => $priv_ids,
        );
        $ret = self::$_db->insert($table,$data);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'insert');
        }
        return $ret;
    }

    /**
     * 修改角色信息
     * @param $role_id
     * @param $role_name
     * @param $priv_ids
     * @return int
     */
    public function update_role($role_id,$data){
        $table = self::$_tbl_role;
        $cond = array('role_id'=>$role_id);
        $ret = self::$_db->update($table,$data,$cond);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'update');
        }
        return $ret;
    }

    /**
     * 删除角色信息
     * @param $role_id
     * @return int
     */
    public function delete_role($role_id){
        $table = self::$_tbl_role;
        $cond = array('role_id'=>$role_id);
        $ret = self::$_db->delete($table,$cond);
        if($ret > 0){
            $this->record_operation_log($this->get_user_name_by_cookie(),'delete');
        }
        return $ret;
    }

    /**
     * 获取角色信息
     * @param $role_id
     * @return array|bool
     */
    public function get_role($role_id){
        $table = self::$_tbl_role;
        $cond = array('role_id'=>$role_id);
        $ret = self::$_db->get($table,'*',$cond);
        return $ret;
    }

    /**
     * 分页批量获取角色信息
     * @param int $page
     * @param int $pagesize
     * @return array|bool
     */
    public function batch_get_role($page = 1,$pagesize = 20){
        $table = self::$_tbl_role;
        $other = " order by role_id asc limit ".($page-1)*$pagesize.",".$pagesize;
        $ret = self::$_db->select($table,"*",array(),$other);
        return $ret;
    }

    /**
     * 分页批量获取操作日志
     * @param int $page
     * @param int $pagesize
     * @return array|bool
     */
    public function batch_get_log($page = 1,$pagesize = 20){
        $table = self::$_tbl_operation_log;
        $other = " order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
        $ret = self::$_db->select($table,"*",array(),$other);
        return $ret;
    }

    public function get_log_count(){
        $table = self::$_tbl_operation_log;
        $ret = self::$_db->select($table,"count(*)");
        return $ret;
    }

    /**
     * 记录操作日志
     * @param $user_name
     * @param $type : insert|update
     * @param $content
     * @return int
     */
    public function record_operation_log($user_name,$type,$content=''){
        $table = self::$_tbl_operation_log;
        $content = $content ? $content : self::$_db->get_last_sql();
        $data = array(
            'user_name' => $user_name,
            'type' => $type,
            'content' => $content,
            'create_time' => time(),
            'ip' => TP_Helper::get_ip(),
        );
        $ret = self::$_db->insert($table,$data);
        return $ret;
    }

}