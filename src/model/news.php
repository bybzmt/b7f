<?php

namespace model;

use b7f;
use mapper;

class news
{
	/**
	 * 添加新闻
	 */
	public function add($type, $title, $content)
	{
		$row = $this->getMapper()->create(0, $type, $title, $content);

		$row->save();

		return $row->id;
	}

	public function getMapper()
	{
		return mapper\news::getInstance();
	}
}
