<?php
namespace widget;

use b7f;
use model;

class user
{
	static public function top()
	{
		$model = new model\user();

		$view = new b7f\view();

		if ($model->isLogin()) {
			$view->login = true;
			$view->url_logout = url('/user/logout', array('to'=>url('/')));
			$view->user = $model->getCurrentUser()->user;
		}
		else {
			$view->login = false;
			$view->url = url('/user/login', array('to'=>url('/')));
		}

		echo $view->reader('tpl/top');
	}
}
