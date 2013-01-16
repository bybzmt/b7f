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
	
	public function edit($id, $token, $msg)
	{
		$mapper = mapper\news::getInstance();

		$row = $mapper->find($id);

		if (!$row) {
			header("HTTP/1.0 404 Not Found");
			return;
		}

		$view = new b7f\view();
		$view->id = $row->id;
		$view->type = $row->type;
		$view->title = $row->title;
		$view->content = $row->content;

		$view->token = $token;
		$view->msg = $msg;
		$view->posturl = url('news/doedit');

		echo $view->reader('news/edit');
	}

	public function showlist($page)
	{
		$length = 10;
		$offset = ($page-1) * $length;

		$mapper = mapper\news::getInstance();

		$rows = $mapper->findByRange($offset, $length);

		$view = new b7f\view();
		$view->rows = $rows;
		echo $view->reader('news/list');
	}
}
