<?php

namespace mapper;

use b7f;
use domain;

class user extends b7f\mapper
{
	static private $instance;

	private function __construct(){}

	public function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getWatcher()
	{
		return get_db_watcher();
	}

	public function getMapperName()
	{
		return 'user';
	}

	public function doMap(array $row)
	{
		$user = new domain\user();
		$user->id = $row['id'];
		$user->user = $row['user'];
		$user->pass = $row['pass'];
		$user->access = $row['access'];

		return $user;
	}

	public function find($id)
	{
		$obj = $this->_find($id);

		if ($obj) {
			return $obj;
		}

		$db = get_db();

		$sql = "SELECT id,user,pass,access FROM users WHERE id={$id} LIMIT 1";

		$row = $db->query($sql)->fetch();

		if ($row) {
			return $this->_doMap($row);
		}

		return null;
	}

	public function findByUser($user)
	{
		$db = get_db();

		$sql = "SELECT id FROM users WHERE user=? LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->execute(array($user));

		$id = $stmt->fetchColumn();

		if ($id) {
			return $this->find($id);
		}

		return null;
	}
}
