<?php
namespace controller;

use view;
use model;

class user
{
	public function loginAction()
	{
		$to_url = isset($_GET['to']) ? $_GET['to'] : null;
		$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

		$hash = mt_rand();

		$_SESSION['login_hash'] = $hash;

		$view = new view\user();
		$view->login($msg, $to_url, $hash);
	}

	public function dologinAction()
	{
		$username = isset($_POST['username']) ? trim($_POST['username']) : null;
		$password = isset($_POST['password']) ? $_POST['password'] : null;
		$code = isset($_POST['code']) ? trim($_POST['code']) : null;
		$hash = isset($_POST['hash']) ? $_POST['hash'] : null;
		$to_url = isset($_POST['to']) ? $_POST['to'] : null;

		$s_code = isset($_SESSION['login_code']) ? $_SESSION['login_code'] : null;
		$s_hash = isset($_SESSION['login_hash']) ? $_SESSION['login_hash'] : null;

		unset($_SESSION['login_hash']);

		$params = array();

		if ($to_url) {
			$params['to'] = $to_url;
		}

		if (!$s_hash || $s_hash != $hash) {
			$params['msg'] = lang('请不要重复提交');
			$url = url('user/login', $params);

			header("Location: {$url}");
			return;
		}

		/*
		if (!$s_code || $s_code != $code) {
			$params['msg'] = lang('验证码错误');
			$url = url('user/login', $params);

			header("Location: {$url}");
			return;
		}
		 */

		$model = new model\user();
		$re = $model->login($username, $password);

		switch ($re) {
		case 1:
		case 2:
			$params['msg'] = lang('用户名或密码错误');
			$url = url('user/login', $params);
			header("Location: {$url}");
			return;
		case 3:
			$params['msg'] = lang('您的帐号己被禁用');
			$url = url('user/login', $params);
			header("Location: {$url}");
			return;
		}

		if ($to_url) {
			header("Location: {$to_url}");
			return;
		}

		header("Location: ".url('/'));
	}

	public function logoutAction()
	{
		$model = new model\user();
		$model->logout();

		$to_url = isset($_GET['to']) ? $_GET['to'] : null;

		if ($to_url) {
			header("Location: {$to_url}");
			return;
		}

		header("Location: ".url('/'));
	}
}
