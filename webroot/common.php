<?php

/**
 * 得到配制
 */
function get_config()
{
	static $config;

	if (!$config) {
		$config = _parse_ini(ENVIRONMENT, 'www-');
	}

	return $config;
}

/**
 * 解析ini
 */
function _parse_ini($env, $prefix='')
{
	$ini_file = BASE_PATH.'/config/'.$prefix.$env.'.ini';

	$inis = parse_ini_file($ini_file, false);

	if (empty($inis)) {
		throw new Exception("配制文件: {$ini_file} 解析异常");
	}

	if (empty($inis['extends'])) {
		return $inis;
	}

	$tmp = _parse_ini($inis['extends'], $prefix);

	return $inis + $tmp;
}

/**
 * 自动载入
 */
function _namespace_autoload($classname)
{
	// 命名空间
	static $namespace;

	if (!$namespace) {
		//命名空间映射
		$namespace = array(
		);
	}

	$space = strtolower($classname);
	$class = '';

	$tmp = explode('\\', $space);

	while ($tmp) {
		if (isset($namespace[$space])) {
			require $namespace[$space] . $class . '.php'; 
		}

		$class .= DIRECTORY_SEPARATOR . array_pop($tmp);
		$space = implode('\\', $tmp);
	}
}

/**
 * 将错误转换成异常
 */
function _ErrorException($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

/**
 * 处理url
 */
function url($uri, $params=null)
{
	static $baseurl;

	if (!$baseurl) {
		$config = get_config();
		$baseurl = '/'.trim($config['baseurl'], '/');
	}

	$uri = trim($uri, '/');

	$url = $baseurl . '/';

	if ($uri) {
		$url .= $uri . '.php';
	}

	if ($params) {
		$tmp = array();
		foreach ($params as $key => $val) {
			$tmp[] = $key .'='.urlencode($val);
		}
		$url .= '?'.implode('&', $tmp);
	}

	return $url;
}

/**
 * 处理语言包
 */
function lang($msg)
{
	return $msg;
}

/**
 * 得到数据库连接
 */
function get_db()
{
	static $db;

	if (!$db) {
		$config = get_config();

		$db = new b7f\db($config['db.dsn'], $config['db.username'], $config['db.password'],
			array(
				PDO::ATTR_TIMEOUT => 10,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_CASE    => PDO::CASE_LOWER,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_STRINGIFY_FETCHES  => false,
			)
		);
	}

	return $db;
}

/**
 * 得到redis连接
 */
function get_redis()
{
	static $redis;

	if (!$redis) {
		$config = get_config();

		$redis = new Redis();
		$redis->connect($config['redis.host'], $config['redis.port']);
		$redis->auth($config['redis.auth']);
		$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
		if (!empty($config['redis.select'])) {
			$redis->select($config['redis.select']);
		}
	}

	return $redis;
}

/**
 * 得到请求缓数据库缓存
 */
function get_db_watcher()
{
	static $watcher;

	if (!$watcher) {
		$watcher = new b7f\watcher(get_db_cache());
	}

	return $watcher;
}

/**
 * 得到数据库缓存
 */
function get_db_cache()
{
	static $cache;

	if (!$cache) {
		$cache = new b7f\cache(get_redis());
	}

	return $cache;
}
