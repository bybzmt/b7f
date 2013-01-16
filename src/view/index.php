<?php

namespace view;

use b7f;
use mapper;

class index
{
	public function index($page)
	{
		$db = get_db();

		$offset = ($page-1) * PAGE_SIZE;
		$length = PAGE_SIZE;

		$mapper = mapper\news::getInstance();

		$rows = $mapper->findByRange($offset, $length);

		$arr = array();
		foreach ($rows as $row) {
			$arr[] = array(
				'title' => $row->title,
				'content' => $row->content,
				'edit_url' => url('news/edit', array('id'=>$row->id)),
			);
		}

		$view = new b7f\view();
		$view->rows = $arr;
		$view->url_add = url('news/add');

		echo $view->reader('index');
	}
}
