<?php

namespace example\input;



class login extends By\Input
{
	function __construct()
	{
		$username = isset($_POST['username']) ? $_POST['username'] : null;
		$password = isset($_POST['password']) ? $_POST['password'] : null;
		$code = isset($_POST['code']) ? $_POST['code'] : null;
		$hash = isset($_POST['hash']) ? $_POST['hash'] : null;

		$s_code = isset($_SESSION['login_code']) ? $_SESSION['login_code'] : null;
		$s_hash = isset($_SESSION['login_hash']) ? $_SESSION['login_hash'] : null;

		parent::__construct(array(
			'username' => $username,
			'password' => $password,
			'code' => $code,
			'hash' => $hash,
			's_code' => $s_code,
			's_hash' => $s_hash,
		);
	}
}
