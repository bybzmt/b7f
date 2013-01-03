<?php

//定义环境
defined('ENVIRONMENT') || define('ENVIRONMENT', 'product');

//调试开关
defined('DEBUG') || define('DEBUG', false);

//定义基础目录
defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__.'/../'));

//配制文件目录
defined('CONFIG_PATH') || define('CONFIG_PATH', BASE_PATH.'/config');

//视图目录
defined('VIEW_PATH') || define('VIEW_PATH', BASE_PATH.'/views');
defined('VIEW_EXT') || define('VIEW_EXT', '.phtml');

//数据目录
defined('VAR_PATH') || define('VAR_PATH', BASE_PATH.'/var');

//第3方库目录
defined('VENDOR_PATH') || define('VENDOR_PATH', BASE_PATH.'/vendors');

#-------------------
# 应用常量
# ------------------
//分页每页长度
define('PAGE_SIZE', 20);

require __DIR__ . '/common.php';

/*自动载入1
spl_autoload_register('_namespace_autoload');
*/

//自动载入2
set_include_path(BASE_PATH.'/src');
spl_autoload_register();

if (DEBUG) {
	error_reporting(E_ALL);
}

//错误处理
set_error_handler('_ErrorException', error_reporting());

session_start();
