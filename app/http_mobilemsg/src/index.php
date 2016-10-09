<?php
$yku_file = YKUSERVERBASEPATH . "/../yku/yku.php";
// 根据环境载入不同的配置文件
$env = get_cfg_var ( 'env' );
if ($env == "") {
    $env = "ol";
}
define ( 'ENV', $env );
define ( 'SERVER_PATH', dirname ( __FILE__ ) );

$user_config = dirname ( __FILE__ ) . '/config/UserConfig.php';
$config = parse_ini_file ( SERVER_INI, true );
//错误处理
set_error_handler ( array (
        '\YkuServer\Core',
        'error_handler' 
) );
// 业务的config
require_once ($yku_file);
// 之后再去run （run 进行路由解析）
return Yku::createHttpApplication ( $user_config ); 