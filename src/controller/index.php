<?php

namespace controller;

use view;

class index
{
	public function indexAction()
	{
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;

		if ($page < 1) {
			$page = 1;
		}

		$view = new view\index();
		$view->index($page);
	}
}
