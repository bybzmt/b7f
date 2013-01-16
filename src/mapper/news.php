<?php

namespace mapper;

use b7f;
use domain;

/**
 * 表户表数据映射器
 */
class news extends b7f\mapper
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
	 * 得到新闻
	 *
	 * @param int $id
	 */
	public function find($id)
	{
		$watcher = get_db_watcher();

		$obj = $watcher->get("news_{$id}");

		if ($obj !== null) {
			return $obj;
		}

		$db = get_db();

		$sql = "SELECT id,type,uid,title,content,addtime FROM news WHERE id={$id} LIMIT 1";

		$row = $db->query($sql)->fetch();

		if ($row) {
			return $this->doMap($row);
		}

		//标记为不存在,防止缓存击穿
		$watcher->makeNotExists("news_{$id}");

		return null;
	}

	/**
	 * 得到一段新闻
	 *
	 * @param int $offset
	 * @param int $length
	 */
	public function findByRange($offset, $length)
	{
		$db = get_db();

		$sql = "SELECT id FROM news LIMIT {$offset},{$length}";
		$stmt = $db->query($sql);

		$rows = $ids = array();

		$watcher = get_db_watcher();

		while($id = $stmt->fetchColumn()) {
			$obj = $watcher->get("news_{$id}");

			if ($obj) {
				$rows[] = $obj;
			}
			else if ($obj === null) {
				$ids[] = $id;
			}
		}

		return array_merge($rows, $this->_getRows($ids));
	}

	/**
	 * 创建一个用户
	 */
	public function create($uid, $type, $title, $content)
	{
		$news = new domain\news();
		$news->id = $this->_getId();
		$news->uid = $uid;
		$news->type = $type;
		$news->title = $title;
		$news->content = $content;
		$news->addtime = date('Y-m-d H:i:s', time());

		return $news;
	}

	/**
	 * 添加
	 */
	public function inserts(array $rows)
	{
		$feilds = array(
			'id',
			'uid',
			'type',
			'title',
			'content',
			'addtime',
		);

		$data = array();

		foreach ($rows as $row) {
			$data[] = array(
				$row->id,
				$row->uid,
				$row->type,
				$row->title,
				$row->content,
				$row->addtime,
			);
		}

		$db = get_db();
		$db->inserts('news', $feilds, $data);
	}

	/**
	 * 更新
	 */
	public function update($row)
	{
		$data = array(
			'type' => $row->type,
			'title' => $row->title,
			'content' => $row->content,
			'addtime' => $row->addtime,
		);

		$db = get_db();
		$db->update('news', $data, "id={$row->id}");
	}

	/**
	 * 删除
	 */
	public function deletes(array $rows)
	{
		$ids = array();

		foreach ($rows as $row) {
			$ids[] = $row->id;
		}

		$db = get_db();
		$db->delete('news', array('id'=>$ids));
	}

	/**
	 * 转据数组到对像
	 */
	public function doMap(array $row)
	{
		$obj = new domain\news();
		$obj->id = $row['id'];
		$obj->uid = $row['uid'];
		$obj->type = $row['type'];
		$obj->title = $row['title'];
		$obj->content = $row['content'];
		$obj->addtime = $row['addtime'];

		get_db_watcher()->keep($obj, true);

		return $obj;
	}

	/**
	 * 得到表的自增id
	 */
	private function _getId()
	{
		$redis = get_redis();

		$id = $redis->incr('tbid_news');

		if ($id > 1) {
			return $id;
		}

		$sql = "SELECT MAX(id) FROM news";

		$db = get_db();
		$stmt = $db->query($sql);
		$id = $stmt->fetchColumn();

		if ($id < 1) {
			$id = 1;
		}

		$redis->setex('tbid_news', 3600, $id);

		return $id;
	}

	private function _getRows($ids)
	{
		if (!$ids) {
			return array();
		}

		$db = get_db();

		$sql = "SELECT id,type,uid,title,content,addtime FROM news WHERE id IN(".implode(',', $ids).")";

		$rows = $db->query($sql)->fetchAll();

		$objs = array();

		foreach ($rows as $row) {
			$objs[] = $this->doMap($row);
		}

		return $objs;
	}

}
