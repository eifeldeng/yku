<?php



class TP_Mysql{

    private $link;     //数据库连接标识;
    static $_instance = array(); //存储对象
    private $_last_sql;//最后执行的sql

    /**
     * 构造函数
     * 私有
     */
    private function __construct($name) {
        $config = TP_Config::get_mysql($name);
        $host = $config['host'];
        $user = $config['user'];
        $pass = $config['pass'];
        $dbname = $config['dbname'];
        $port = isset($config['port']) ? $config['port'] : 3306;

        $this->link = mysqli_init();
        $this->link->options(MYSQLI_OPT_CONNECT_TIMEOUT, 2);
        $this->link->real_connect($host, $user, $pass, $dbname, $port);
        if(mysqli_connect_errno()){
            TP_Log::error("mysqli_connect_failed:".mysqli_connect_error());
            throw new Exception(Config_Errmsg::ERR_MYSQL_CONNECT_FAIL,Config_Errno::ERR_MYSQL_CONNECT_FAIL);
        }
        $this->query("SET NAMES 'utf8'");
        return $this->link;
    }

    /**
     * 防止被克隆
     *
     */
    private function __clone(){}
    public static function get_instance($name){
        if(!isset(self::$_instance[$name])){
            self::$_instance[$name] = new self($name);
        }
        return self::$_instance[$name];
    }

    /**
     * 获取连接符标识
     * @return mysqli
     */
    public function get_link(){
        return $this->link;
    }

    /**
     * 查询
     * @param $sql
     * @return bool|mysqli_result
     */
    public function query($sql) {
        $result = mysqli_query($this->link,$sql) or $this->err($sql);
        $this->_last_sql = $sql;
        return $result;
    }

    /**
     * 插入记录
     * @param $sql
     * @return int
     */
    public function insert_by_sql($sql){
        $this->query($sql);
        return mysqli_affected_rows($this->link);
    }

    /**
     * 更新记录
     * @param $sql
     * @return int
     */
    public function update_by_sql($sql){
        $this->query($sql);
        return mysqli_affected_rows($this->link);
    }

    /**
     * 删除记录
     * @param $sql
     * @return int
     */
    public function delete_by_sql($sql){
        $this->query($sql);
        return mysqli_affected_rows($this->link);
    }

    /**
     * 获取单行记录
     * @param $sql
     * @param int $type
     * @return array|bool
     */
    public function get_one_by_sql($sql, $type = MYSQL_ASSOC) {
        $result = $this->query($sql);
        if (!$result) return false;
        return mysqli_fetch_array($result, $type);
    }

    /**
     * 获取多行记录
     * @param $sql
     * @param int $type
     * @return array|bool
     */
    public function get_all_by_sql($sql, $type = MYSQL_ASSOC){
        $result = $this->query($sql);
        if (!$result) return false;
        $rows = array();
        while ($row = mysqli_fetch_array($result, $type)) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * 获取记录数量
     * @param $sql
     * @return bool|int
     */
    public function get_count_by_sql($sql){
        $result = $this->query($sql);
        if (!$result) return false;
        return mysqli_num_rows($result);
    }

    /**
     * 按条件查询一条记录
     * @param $table
     * @param $columns
     * @param $where
     * @param string $other
     * @return array
     */
    public function get($table, $columns = '*' , $where = array() , $other = 'limit 1') {
        $cond = '1=1';
        if(!empty($where) && is_array($where)){
            foreach ($where as $k => $v) {
                $cond .= " AND `$k` = ".$this->escape($v);
            }
        }
        $sql = "SELECT $columns FROM `{$table}` WHERE $cond $other";
        return $this->get_one_by_sql($sql);
    }

    /**
     * 按条件查询多条记录
     * @param $table
     * @param $columns
     * @param $where
     * @param string $other
     * @return array
     */
    public function select($table, $columns = '*', $where = array(), $other = 'limit 100') {
        $cond = '1=1';
        if(!empty($where) && is_array($where)){
            foreach ($where as $k => $v) {
                $cond .= " AND `$k` = ".$this->escape($v);
            }
        }
        $sql = "SELECT $columns FROM `{$table}` WHERE $cond $other";
        return $this->get_all_by_sql($sql);
    }

    /**
     * 插入数据
     * @param $table
     * @param $row
     * @return int
     */
    public function insert($table, $row) {
        $stat = '';
        foreach ($row as $k => $v) {
            $stat .= "`$k` = ".$this->escape($v).",";
        }
        $stat = substr($stat, 0, strlen($stat) - 1);
        $sql = "INSERT INTO `{$table}` SET $stat";
        return $this->insert_by_sql($sql);
    }

    /**
     * 批量插入
     * @param $table
     * @param $rows
     * @return int
     */
    public function insert_batch($table,$rows){
        $ar_keys = array_keys(current($rows));
        $ar_values = array();
        foreach ($rows as $row) {
            $clean = array();
            foreach ($row as $value) {
                $clean[] = $this->escape($value);
            }
            $ar_values[] =  '('.implode(',', $clean).')';
        }
        $sql =  "INSERT INTO ".$table." (".implode(', ', $ar_keys).") VALUES ".implode(', ', $ar_values);
        return $this->insert_by_sql($sql);
    }

    /**
     * 更新数据
     * @param $table
     * @param $row
     * @param $where
     * @return int
     */
    public function update($table, $row, $where) {
        $stat = '';
        foreach ($row as $k => $v) {
            $stat .= "`$k` = ".$this->escape($v).",";
        }
        $stat = substr($stat, 0, strlen($stat) - 1);

        $cond = '';
        foreach ($where as $k => $v) {
            $cond .= "`$k` = ".$this->escape($v)." AND ";
        }
        $cond = substr($cond, 0, strlen($cond) - 5);

        $sql = "UPDATE `{$table}` SET $stat WHERE $cond";
        return $this->update_by_sql($sql);
    }

    /**
     * 插入数据
     * @param $table
     * @param $row
     * @return int
     */
    public function delete($table, $where) {
        $cond = '';
        foreach ($where as $k => $v) {
            $cond .= "`$k` = ".$this->escape($v).",";
        }
        $cond = substr($cond, 0, strlen($cond) - 1);
        $sql = "DELETE FROM `{$table}` WHERE $cond";
        return $this->delete_by_sql($sql);
    }

    /**
     * 插入或更新数据
     * @param $table
     * @param $row
     */
    public function insert_or_update($table, $row) {
        $stat = '';
        foreach ($row as $k => $v) {
            $stat .= "`$k` = ".$this->escape($v).",";
        }
        $stat = substr($stat, 0, strlen($stat) - 1);
        $sql = "INSERT INTO `{$table}` SET $stat ON DUPLICATE KEY UPDATE $stat";
        return $this->insert_by_sql($sql);
    }

    /**
     * 获取最后一次执行的sql
     * @return mixed
     */
    public function get_last_sql(){
        return $this->_last_sql;
    }

    /**
     * @param $str
     * @return int|string
     */
    public function escape($str){
        if (is_string($str)) {
            $str = "'".$this->escape_str($str)."'";
        }
        elseif (is_bool($str)) {
            $str = ($str === FALSE) ? 0 : 1;
        }
        elseif (is_null($str)) {
            $str = 'NULL';
        }
        return $str;
    }

    /**
     * @param $str
     * @param bool $like
     * @return array|mixed|string
     */
    function escape_str($str, $like = FALSE){
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escape_str($val, $like);
            }
            return $str;
        }

        $str = mysqli_real_escape_string($this->link, $str);

        // escape LIKE condition wildcards
        if ($like === TRUE) {
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
        }

        return $str;
    }

    /**
     * 错误信息记录日志
     * @param null $sql
     */
    protected function err($sql = null) {
        TP_Log::error("mysqli_errno:".mysqli_errno($this->link)."|mysqli_error:".mysqli_error($this->link)."|sql:".$sql);
    }

    public function __destruct(){
        $this->link->close();
    }

}
