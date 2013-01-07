<?php

namespace view;

use b7f;

class news
{
	public function add()
	{
		$view = new b7f\view();
		$view->posturl = url('news/doadd');

		echo $view->reader('news/add');
	}
}
