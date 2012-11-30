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

//数据目录
defined('VAR_PATH') || define('VAR_PATH', BASE_PATH.'/var');

//第3方库目录
defined('VENDOR_PATH') || define('VENDOR_PATH', BASE_PATH.'/vendors');

//require 'by/loader.php';
//$loader = new \By\Loader();
//$loader->autoload(true);

//类库
set_include_path(BASE_PATH.'/src');

//启用默认自动载入
spl_autoload_register();

//错误处理
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if (DEBUG) {
	error_reporting(E_ALL);
}
