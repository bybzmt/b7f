<?php
#author:bybzmt

namespace domain;

use b7f;
use mapper;

/**
 * 用户数据行
 */
class user extends b7f\row
{
	public $id;
	public $user;
	public $pass;
	public $access;

	public function getId()
	{
		return "user_{$this->id}";
	}

	public function getMapper()
	{
		return mapper\user::getInstance();
	}

	public function getWatcher()
	{
		return get_db_watcher();
	}
}
