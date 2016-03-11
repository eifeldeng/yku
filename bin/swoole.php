<?php
define ( 'STARTBASEPATH', dirname ( dirname ( __FILE__ ) ) );
define ( 'SuperProcessName', 'Swoole-Controller' );

// php swoole.php testserver start

$name = isset ( $argv [2] ) ? $argv [2] : '';
$cmd = $argv [1]; // cmd name
                  
// 输出所有可以执行的server
if ($cmd == 'list') {
    $configDir = STARTBASEPATH . "/conf/*.ini";
    $configArr = glob ( $configDir );
    // 配置名必须是servername
    $servArr = array ();
    echo "your server list：" . PHP_EOL;
    
    echo '----------------------------' . PHP_EOL;
    foreach ( $configArr as $k => $v ) {
        echo basename ( $v, '.ini' ) . PHP_EOL;
    }
    
    echo '----------------------------' . PHP_EOL;
    exit ();
} elseif ($cmd == 'start') {
    if ($name == null) {
        echo 'please choose your server ' . PHP_EOL;
        exit ();
    }
    $pid = getServerMasterPid ( $name );
    if ($pid && posix_kill ( $pid, 0 )) {
        echo "\033[31;40m [FAIL] \033[0m server [" . $name . '] is already running' . PHP_EOL;
        exit ();
    }
    
    $indexConf = getServerIni ( $name );
    if ($indexConf ['r'] != 0) { //
        echo "\033[31;40m [FAIL] \033[0m  get server $name conf error" . PHP_EOL;
        exit ();
    }
    $phpStart = $indexConf ['conf'] ['server'] ['php'];
    if (empty ( $phpStart )) {
        echo "\033[31;40m [FAIL] \033[0m server $name phpstartpath $phpStart not exist " . PHP_EOL;
        exit ();
    }
    // 先处理单个 注意异常处理的情况
    $process = new swoole_process ( function (swoole_process $worker) use($name, $cmd, $phpStart) { // 目前指支持一个
        $worker->exec ( $phpStart, array (
                STARTBASEPATH . "/lib/Swoole/shell/start.php",
                $cmd,
                $name 
        ) );
    }, false );
    $pid = $process->start ();
    $exeRet = swoole_process::wait ();
    if ($exeRet ['code']) { // 创建失败
        echo $phpStart . ' ' . $name . ' ' . $cmd . "\033[31;40m [FAIL] \033[0m" . PHP_EOL;
        return;
    }
    echo $phpStart . ' ' . $name . ' ' . $cmd . "\033[32;40m [SUCCESS] \033[0m" . PHP_EOL;
} elseif ($cmd == "stop") {
    if ($name == null) {
        echo '\033[31;40m [FAIL] \033[0m  please choose your server ' . PHP_EOL;
        exit ();
    }
    $pid = getServerMasterPid ( $name );
    if ($pid == false) {
        echo "\033[31;40m [FAIL] \033[0m  server [" . $name . "] pid file not exist" . PHP_EOL;
        exit ();
    }
    
    if (posix_kill ( $pid, SIGTERM )) { // 如果成功了
        echo 'stop [' . $name . "] \033[32;40m [SUCCESS] \033[0m" . PHP_EOL;
    } else {
        echo 'stop [' . $name . "] \033[31;40m [FAIL] \033[0m server is not running" . PHP_EOL;
        exit ();
    }
    sleep ( 2 );
    shell_exec ( "kill -9 $pid" );
} elseif ($cmd == "reload") {
    if ($name == null) {
        echo '\033[31;40m [FAIL] \033[0m  please choose your server ' . PHP_EOL;
        exit ();
    }
    $pid = getServerMasterPid ( $name );
    if ($pid == false) {
        echo "\033[31;40m [FAIL] \033[0m  server [" . $name . "] pid file not exist" . PHP_EOL;
        exit ();
    }
    
    if (posix_kill ( $pid, SIGUSR1 )) { // 如果成功了
        echo 'reload [' . $name . "] \033[32;40m [SUCCESS] \033[0m" . PHP_EOL;
    } else {
        echo 'reload [' . $name . "] \033[31;40m [FAIL] \033[0m" . PHP_EOL;
    }
} elseif ($cmd == "status") {
    
    echo "*****************************************************************" . PHP_EOL;
    echo "Summary:" . PHP_EOL;
    echo "Swoole Version:" . SWOOLE_VERSION . PHP_EOL;
    if ($name == null) {
        echo '\033[31;40m [FAIL] \033[0m  please choose your server ' . PHP_EOL;
        exit ();
    }
    $pid = getServerMasterPid ( $name );
    if ($pid == false) {
        echo "\033[31;40m [FAIL] \033[0m  server [" . $name . "] is not running " . PHP_EOL;
    }
    if ($pid && posix_kill ( $pid, 0 )) {
        echo $name . ": is running \033[32;40m [SUCCESS] \033[0m" . PHP_EOL;
        echo "master pid :  " . $pid . PHP_EOL;
        echo "manager pid :  " . getServerManagerPid ( $name ) . PHP_EOL;
    } else {
        echo $name . ": is  not running " . PHP_EOL;
    }
    echo "*****************************************************************" . PHP_EOL;
} else {
    echo " USAGE :  option [start | stop | status | list | reload ] server" . PHP_EOL;
}
function getServerIni($serverName) {
    $configPath = STARTBASEPATH . "/conf/" . $serverName . ".ini";
    if (! file_exists ( $configPath )) {
        return array (
                'r' => 404,
                'msg' => 'missing config path' . $configPath 
        );
    }
    $config = parse_ini_file ( $configPath, true );
    return array (
            'r' => 0,
            'conf' => $config 
    );
}
function StartLog($msg) {
    error_log ( $msg . PHP_EOL, 3, '/tmp/SuperMaster.log' );
}
function StartLogTimer($msg) {
    error_log ( $msg . PHP_EOL, 3, '/tmp/SuperMasterTimer.log' );
}
function StartServ($phpStart, $cmd, $name) {
    $process = new swoole_process ( function (swoole_process $worker) use($name, $cmd, $phpStart) { // 目前指支持一个
        $worker->exec ( $phpStart, array (
                STARTBASEPATH . "/lib/Swoole/shell/start.php",
                $cmd,
                $name 
        ) ); // 拉起server
        StartLogTimer ( __LINE__ . '   ' . $phpStart . ' ' . STARTBASEPATH . '/lib/Swoole/shell/start.php ' . $cmd . ' ' . $name );
    }, false );
    $pid = $process->start ();
    $exeRet = swoole_process::wait ();
    if ($exeRet ['code']) { // 创建失败
        StartLog ( " startall  \033[31;40m [FAIL] \033[0m" . PHP_EOL );
        return false;
    } else {
        StartLog ( " startall  \033[31;40m [SUCCESS] \033[0m" . PHP_EOL );
        return true;
    }
}

/**
 * 获取服务的pid
 *
 * @param string $name            
 *
 */
function getServerMasterPid(string $name) {
    $pid = false;
    $master_pid_file = '/tmp/' . $name . ".master.pid";
    if (file_exists ( $master_pid_file )) {
        $pid = file_get_contents ( $master_pid_file );
    }
    return $pid;
}
function getServerManagerPid(string $name) {
    $pid = false;
    $master_pid_file = '/tmp/' . $name . ".manager.pid";
    if (file_exists ( $master_pid_file )) {
        $pid = file_get_contents ( $master_pid_file );
    }
    return $pid;
}

