<?php
#author:bybzmt

namespace domain;

use b7f;
use mapper;

/**
 * 新闻数据行
 */
class news extends b7f\row
{
	public $id;
	public $uid;
	public $type;
	public $title;
	public $content;
	public $addtime;

	public function getId()
	{
		return "news_{$this->id}";
	}

	public function getMapper()
	{
		return mapper\news::getInstance();
	}

	public function getWatcher()
	{
		return get_db_watcher();
	}
}
