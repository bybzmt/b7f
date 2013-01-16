<?php

namespace mapper;

use b7f;
use domain;

/**
 * 表户表数据映射器
 */
class user extends b7f\mapper
{
	static private $instance;

	private function __construct(){}

	static public function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * 得到用户
	 *
	 * @param int $id
	 */
	public function find($id)
	{
		$watcher = get_db_watcher();

		$obj = $watcher->get("user_{$id}");

		if ($obj !== null) {
			return $obj;
		}

		$db = get_db();

		$sql = "SELECT id,user,pass,access FROM users WHERE id={$id} LIMIT 1";

		$row = $db->query($sql)->fetch();

		if ($row) {
			return $this->doMap($row);
		}

		//标记为不存在,防止缓存击穿
		$watcher->makeNotExists("user_{$id}");

		return null;
	}

	/**
	 * 根据用户名查找用户
	 *
	 * @param string $user
	 */
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

	/**
	 * 创建一个用户
	 */
	public function create($user, $pass)
	{
		$user = new domain\user();
		$user->id = $this->_getId();
		$user->user = $user;

		return $user;
	}

	/**
	 * 添加
	 */
	public function inserts(array $rows)
	{
		$feilds = array(
			'id',
			'user',
			'pass',
			'access',
		);

		$data = array();

		foreach ($rows as $row) {
			$data[] = array(
				'id' => $row->id,
				'user' => $row->user,
				'pass' => $row->pass,
				'access' => $row->access,
			);
		}

		$db = get_db();
		$db->inserts('user', $feilds, $data);
	}

	/**
	 * 更新
	 */
	public function update($row)
	{
		$data = array(
			'user' => $row->user,
			'pass' => $row->pass,
			'access' => $row->access,
		);

		$db = get_db();
		$db->update('user', $data, "id={$row->id}");
	}

	/**
	 * 删除
	 */
	public function deletes(array $row)
	{
		$ids = array();

		foreach ($rows as $row) {
			$ids[] = $row->id;
		}

		$db = get_db();
		$db->delete('user', array('id'=>$ids));
	}

	/**
	 * 转据数组到对像
	 */
	public function doMap(array $row)
	{
		$user = new domain\user();
		$user->id = $row['id'];
		$user->user = $row['user'];
		$user->pass = $row['pass'];
		$user->access = $row['access'];

		get_db_watcher()->keep($user, true);

		return $user;
	}

	/**
	 * 得到表的自增id
	 */
	private function _getId()
	{
		$redis = get_redis();

		$id = $redis->incr('tbid_user');

		if ($id > 1) {
			return $id;
		}

		$sql = "SELECT MAX(id) FROM user";

		$db = get_db();
		$stmt = $db->query($sql);
		$id = $stmt->fetchColumn();

		if ($id < 1) {
			$id = 1;
		}

		$redis->setex('tbid_user', 3600, $id);

		return $id;
	}

}
