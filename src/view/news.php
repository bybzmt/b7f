<?php

namespace view;

use b7f;
use mapper;

class news
{
	public function add($token, $msg)
	{
		$view = new b7f\view();
		$view->posturl = url('news/doadd');
		$view->token = $token;
		$view->msg = $msg;

		echo $view->reader('news/add');
	}

	public function read($id)
	{
		$mapper = mapper\news::getInstance();

		$row = $mapper->find($id);

		if (!$row) {
			header("HTTP/1.0 404 Not Found");
			return;
		}

		$view = new b7f\view();
		$view->title = $row->title;
		$view->content = $row->content;

		echo $view->reader('news/read');
	}

	public function showlist($page)
	{
		$length = 10;
		$offset = ($page-1) * $length;

		$mapper = mapper\news::getInstance();

		$rows = $mapper->findByRange($offset, $length);

		$arr = array();
		foreach ($rows as $row) {
			$arr[] = (array)$row;
		}

		$view = new b7f\view();
		$view->rows = $arr;
		echo $view->reader('news/list');
	}
}
