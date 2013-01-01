<?php

namespace Example;

use By;

require __DIR__.'/../bootstrap.php';

$controller = new user\contrller\index();
$controller->dologinAction();

$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$code = isset($_POST['code']) ? trim($_POST['code']) : null;
$hash = isset($_POST['hash']) ? $_POST['hash'] : null;
$to_url = isset($_POST['to']) ? $_POST['to'] : null;

$s_code = isset($_SESSION['login_code']) ? $_SESSION['login_code'] : null;
$s_hash = isset($_SESSION['login_hash']) ? $_SESSION['login_hash'] : null;

unset($_SESSION['login_hash']);

if (!$s_hash || $s_hash != $hash) {
	$url = url('login', array('msg'=>lang('请不要重复提交'), 'to'=>$to_url));

	header("Location: {$url}");
	die;
}

if (!$s_code || $s_code != $code) {
	$url = url('login', array('msg'=>lang('验证码错误'), 'to'=>$to_url));

	header("Location: {$url}");
	die;
}

$db = new db();

$sql = "SELECT id,user,pass,name,access FROM user WHERE user='%s' LIMIT 1";

$re = $db->fetchOne($sql, $username);

//用户不存在
if (!$row) {
	$url = url('login', array('msg'=>lang('用户名或密码错误'), 'to'=>$to_url));

	header("Location: {$url}");
	die;
}

//密码错误
if (md5(USER_SECURITY.$row['id'].$password) != $row['pass']) {
	$url = url('login', array('msg'=>lang('用户名或密码错误'), 'to'=>$to_url));

	header("Location: {$url}");
	die;
}

if ($row['access']) {
	$url = url('login', array('msg'=>lang('您的帐号己被禁用'), 'to'=>$to_url));

	header("Location: {$url}");
	die;
}

//记录信息
$_SESSION['user_id'] = $row['id'];
$_SESSION['user_name'] = $row['user'];
$_SESSION['user_access'] = $row['access'];

header("Location: {$to_url}");
