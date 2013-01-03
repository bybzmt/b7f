<?php

namespace view;

use b7f;

class user
{
	public function login($msg, $to_url, $hash)
	{
		$view = new b7f\view();
		$view->msg = $msg;
		$view->to_url = $to_url;
		$view->hash = $hash;
		$view->posturl = url('user/dologin');

		echo $view->reader('login');
	}
}
