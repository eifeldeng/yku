<?php

namespace YkuServer;

abstract class Server implements \YkuServer\Server\Driver {
    public $protocol;
    public $config = array ();
    protected $sw;
    protected $processName = 'YkuServ';
    protected $host = '0.0.0.0';
    protected $port = 8088;
    protected $listen;
    protected $mode = SWOOLE_PROCESS;
    protected $sockType;
    protected $udpListener;
    protected $tcpListener;
    protected $setting = array ();
    protected $runPath = '/tmp';
    protected $masterPidFile;
    protected $managerPidFile;
    protected $user;
    protected $enableHttp = false;
    private $requireFile = '';
    public function __construct() {
        $this->setting = array_merge ( array (
                'worker_num' => 8, // worker process num
                'backlog' => 128, // listen backlog
                'log_file' => '/tmp/yku_server.log' 
        ), // server log
$this->setting );
        $this->setHost ();
    }
    public function setRequire($file) {
        if (! file_exists ( $file )) {
            throw new \Exception ( "[error] require file :$file is not exists" );
        }
        $this->requireFile = $file;
    }
    public function setProcessName($processName) {
        $this->processName = $processName;
    }
    public function setConfig($config = array()) {
        if (is_string ( $config )) { // $config is file path?
            if (! file_exists ( $config )) {
                throw new \Exception ( "[error] profiles [$config] can not be loaded" );
            }
            // Load the configuration file into an array
            $config = parse_ini_file ( $config, true );
        }
        if (is_array ( $config )) {
            $this->config = array_merge ( $this->config, $config );
        }
        
        return true;
    }
    protected function _initRunTime() {
        $mainSetting = $this->config ['server'] ? $this->config ['server'] : array ();
        $runSetting = $this->config ['setting'] ? $this->config ['setting'] : array ();
        $this->masterPidFile = $this->runPath . '/' . $this->processName . '.master.pid';
        $this->managerPidFile = $this->runPath . '/' . $this->processName . '.manager.pid';
        $this->setting = array_merge ( $this->setting, $runSetting );
        $this->setting ['worker_num'] = intval ( $this->setting ['worker_num'] );
        $this->setting ['dispatch_mode'] = intval ( $this->setting ['dispatch_mode'] );
        $this->setting ['daemonize'] = intval ( $this->setting ['daemonize'] );
        
        // 设置监听的端口
        if ($mainSetting ['listen']) {
            $this->transListener ( $mainSetting ['listen'] );
        }
        
        if (isset ( $mainSetting ['user'] )) {
            $this->user = $mainSetting ['user'];
        }
        
        if ($this->listen [0]) {
            $this->host = $this->listen [0] ['host'] ? $this->listen [0] ['host'] : $this->host;
            $this->port = $this->listen [0] ['port'] ? $this->listen [0] ['port'] : $this->port;
            unset ( $this->listen [0] );
        }
    }
    private function initServer() {
        $swooleServerName = $this->enableHttp ? '\swoole_http_server' : '\swoole_server';
        
        $this->sw = new $swooleServerName ( $this->host, $this->port, $this->mode, $this->sockType );
        $this->sw->set ( $this->setting );
        
        $this->sw->on ( 'Start', array (
                $this,
                'onMasterStart' 
        ) );
        $this->sw->on ( 'ManagerStart', array (
                $this,
                'onManagerStart' 
        ) );
        $this->sw->on ( 'WorkerStart', array (
                $this,
                'onWorkerStart' 
        ) );
        $this->sw->on ( 'Connect', array (
                $this,
                'onConnect' 
        ) );
        $this->sw->on ( 'Receive', array (
                $this,
                'onReceive' 
        ) );
        $this->sw->on ( 'Close', array (
                $this,
                'onClose' 
        ) );
        $this->sw->on ( 'WorkerStop', array (
                $this,
                'onWorkerStop' 
        ) );
        if ($this->enableHttp) {
            $this->sw->on ( 'Request', array (
                    $this,
                    'onRequest' 
            ) );
        }
        if (isset ( $this->setting ['task_worker_num'] )) {
            $this->sw->on ( 'Task', array (
                    $this,
                    'onTask' 
            ) );
            $this->sw->on ( 'Finish', array (
                    $this,
                    'onFinish' 
            ) );
        }
        
        if (is_array ( $this->listen )) {
            foreach ( $this->listen as $v ) {
                if (! $v ['host'] || ! $v ['port']) {
                    continue;
                }
                $this->sw->addlistener ( $v ['host'], $v ['port'], $this->sockType );
            }
        }
    }
    private function transListener($listen) {
        if (! is_array ( $listen )) {
            $tmpArr = explode ( ":", $listen );
            $host = isset ( $tmpArr [1] ) ? $tmpArr [0] : $this->host;
            $port = isset ( $tmpArr [1] ) ? $tmpArr [1] : $tmpArr [0];
            
            $this->listen [] = array (
                    'host' => $host,
                    'port' => $port 
            );
            return true;
        }
        foreach ( $listen as $v ) {
            $this->transListener ( $v );
        }
    }
    /**
     * 主进行启动
     *
     * @param unknown $server            
     */
    public function onMasterStart($server) {
        Console::setProcessName ( $this->processName . ': master process' );
        
        file_put_contents ( $this->masterPidFile, $server->master_pid );
        file_put_contents ( $this->managerPidFile, $server->manager_pid );
        if ($this->user) {
            Console::changeUser ( $this->user );
        }
    }
    
    /**
     * 管理进程启动
     *
     * @param unknown $server            
     */
    public function onManagerStart($server) {
        Console::setProcessName ( $this->processName . ': manager process' );
        if ($this->user) {
            Console::changeUser ( $this->user );
        }
    }
    /**
     * worker进程启动
     *
     * @param unknown $server            
     * @param unknown $workerId            
     * @throws \Exception
     */
    public function onWorkerStart($server, $workerId) {
        if ($workerId >= $this->setting ['worker_num']) {
            Console::setProcessName ( $this->processName . ': task worker process' );
        } else {
            Console::setProcessName ( $this->processName . ': event worker process' );
        }
        
        if ($this->user) {
            Console::changeUser ( $this->user );
        }
        // 执行框架的载入文件,http_server/src/index.php
        // 返回一个YKUHttpServ,或者TCPserver
        $protocol = (require_once $this->requireFile); // 执行
        
        $this->setProtocol ( $protocol );
        if (! $this->protocol) {
            throw new \Exception ( "[error] the protocol class  is empty or undefined" );
        }
        
        // 为server创建一个调度器
        $this->protocol->onStart ( $server, $workerId );
    }
    public function onConnect($server, $fd, $fromId) {
        // $this->log("Client connected : fd=$fd|fromId=$fromId");
        $this->protocol->onConnect ( $server, $fd, $fromId );
    }
    public function onTask($server, $taskId, $fromId, $data) {
        $this->protocol->onTask ( $server, $taskId, $fromId, $data );
    }
    public function onFinish($server, $taskId, $data) {
        $this->protocol->onFinish ( $server, $taskId, $data );
    }
    public function onClose($server, $fd, $fromId) {
        $this->protocol->onClose ( $server, $fd, $fromId );
    }
    public function onWorkerStop($server, $workerId) {
        $this->protocol->onShutdown ( $server, $workerId );
    }
    public function onTimer($server, $interval) {
        $this->protocol->onTimer ( $server, $interval );
    }
    public function onRequest($request, $response) {
        /*
         * 设定一个全局的协程调度对象
         */
        $request->scheduler = $this->sw->scheduler;
        $this->protocol->onRequest ( $request, $response );
    }
    public function onReceive($server, $fd, $fromId, $data) {
        $this->protocol->onReceive ( $server, $fd, $fromId, $data );
    }
    public function setProtocol($protocol) {
        if (! ($protocol instanceof \YkuServer\Server\Protocol)) {
            throw new \Exception ( "[error] The protocol is not instanceof \\YkuServer\\Server\\Protocol" );
        }
        $this->protocol = $protocol;
        $this->protocol->server = $this->sw;
    }
    /**
     *
     * {@inheritDoc}
     *
     * @see \YkuServer\Server\Driver::run()
     */
    public function run() {
        // 初始化server配置资源
        $this->_initRunTime ();
        // 创建一个server,并设置回调函数
        $this->initServer ();
        // 起飞!
        $this->start ();
    }
    protected function start() {
        $this->log ( $this->processName . ": start\033[31;40m [OK] \033[0m" );
        $this->sw->start ();
    }
    protected function getMasterPid() {
        $pid = false;
        if (file_exists ( $this->masterPidFile )) {
            $pid = file_get_contents ( $this->masterPidFile );
        }
        return $pid;
    }
    protected function getManagerPid() {
        $pid = false;
        if (file_exists ( $this->managerPidFile )) {
            $pid = file_get_contents ( $this->managerPidFile );
        }
        return $pid;
    }
    protected function checkServerIsRunning() {
        $pid = $this->getMasterPid ();
        return $pid && $this->checkPidIsRunning ( $pid );
    }
    protected function checkPidIsRunning($pid) {
        return posix_kill ( $pid, 0 );
    }
    public function close($client_id) {
        swoole_server_close ( $this->sw, $client_id );
    }
    public function send($client_id, $data) {
        swoole_server_send ( $this->sw, $client_id, $data );
    }
    public function daemonize() {
        $this->setting ['setting'] ['daemonize'] = 1;
    }
    protected function setHost() {
        $this->host = '0.0.0.0';
    }
    public function log($msg) {
        if (is_string ( $msg ) == false) {
            $msg = print_r ( $msg, true );
        }
        $msg = "[" . date ( "Y-m-d H:i:s" ) . "] " . $msg;
        $log_file_info = pathinfo (  $this->sw->setting ['log_file']  );
        if (!is_dir($log_file_info['dirname'])) {
            mkdir($log_file_info['dirname'],0777);
        }
        if ($this->sw->setting ['log_file'] && file_exists ( $this->sw->setting ['log_file'] )) {
            error_log ( $msg . PHP_EOL, 3, $this->sw->setting ['log_file'] );
        }
        echo $msg . PHP_EOL;
    }
}



