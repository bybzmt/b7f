<?php
namespace controller;

use view;
use model;

class news
{
	public function addAction()
	{
		$view = new view\news();
		$view->add();
	}
}
