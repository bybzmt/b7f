<?php

namespace view;

use b7f;

class index
{
	public function index($page)
	{
		$db = get_db();

		$offset = ($page-1) * PAGE_SIZE;
		$length = PAGE_SIZE;

		$sql = "SELECT * FROM news ORDER BY id LIMIT {$offset}, {$length}";

		$rows = $db->query($sql)->fetchAll();

		$view = new b7f\view();
		$view->rows = $rows;

		echo $view->reader('index');
	}
}
