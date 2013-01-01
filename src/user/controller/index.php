<?php
namespace user\controller;

class index
{
	public function loginAction()
	{
		$to_url = isset($_GET['to_url']) ? $_GET['to_url'] : null;
		$msg = isset($_GET['msg']) ? $_GET['msg'] : null;

		$view = new b7f\View();
		$view->msg = $msg;
		$view->to_url = $to_url;

		echo $view->reader('login');
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

		if (!$s_hash || $s_hash != $hash) {
			$url = url('login', array('msg'=>lang('请不要重复提交'), 'to'=>$to_url));

			header("Location: {$url}");
			return;
		}

		if (!$s_code || $s_code != $code) {
			$url = url('login', array('msg'=>lang('验证码错误'), 'to'=>$to_url));

			header("Location: {$url}");
			return;
		}

		$db = new db();

		$sql = "SELECT id,user,pass,name,access FROM user WHERE user='%s' LIMIT 1";

		$re = $db->fetchOne($sql, $username);

		//用户不存在
		if (!$row) {
			$url = url('login', array('msg'=>lang('用户名或密码错误'), 'to'=>$to_url));

			header("Location: {$url}");
			return;
		}

		//密码错误
		if (md5(USER_SECURITY.$row['id'].$password) != $row['pass']) {
			$url = url('login', array('msg'=>lang('用户名或密码错误'), 'to'=>$to_url));

			header("Location: {$url}");
			return;
		}

		if ($row['access']) {
			$url = url('login', array('msg'=>lang('您的帐号己被禁用'), 'to'=>$to_url));

			header("Location: {$url}");
			return;
		}

		//记录信息
		$_SESSION['user_id'] = $row['id'];
		$_SESSION['user_name'] = $row['user'];
		$_SESSION['user_access'] = $row['access'];

		header("Location: {$to_url}");
	}

	public function logoutAction()
	{
		session_dristry();
		header("Location: /");
	}
}
