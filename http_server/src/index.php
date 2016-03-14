<?php
// 加载yku的内容
$yku_file = SWOOLEBASEPATH . "/../yku/yku.php";

$user_config = dirname ( __FILE__ ) . '/config/UserConfig.php';
$config = parse_ini_file ( SERVER_INI, true );
// 根据ini配置的环境载入不同的配置文件
$env = $config ['server'] ['env'];
require_once dirname ( __FILE__ ) . '/config/envcnf/' . $env . '/ENVConst.php';
// 业务的config
require_once ($yku_file);
// 之后再去run （run 进行路由解析）
return Yku::createHttpApplication ( $user_config ); 