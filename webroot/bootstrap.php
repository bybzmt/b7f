<?php

//定义环境
defined('ENVIRONMENT') || define('ENVIRONMENT', 'PRODUCT');

//调试开关
defined('DEBUG') || define('DEBUG', false);

//定义基础目录
defined('BASEPATH') || define('BASEPATH', __DIR__);

//类库
set_include_path(__DIR__.'/../lib');

//视图目录
defined('VIEWPATH') || define('VIEWPATH', BASEPATH.'/views');

//require 'by/loader.php';

//$loader = new \By\Loader();
//$loader->autoload(true);

//启用默认自动载入
spl_autoload_register();

//错误处理
set_error_handler(function($errno, $errstr, $errfile, $errline){
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if (DEBUG) {
	error_reporting(E_ALL);
}
