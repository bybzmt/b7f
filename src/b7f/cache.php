<?php
namespace b7f;

use \redis;

/**
 * 数据库缓存
 */
class cache
{
	protected $_redis;

	public function __construct(redis $redis)
	{
		$this->_redis = $redis;
	}

	public function add(row $row)
	{
		$this->_redis->set($row->getId(), serialize($row));

		$this->_redis->rpush('watcher_add', $row->getId());
	}

	public function edit(row $row)
	{
		$this->_redis->set($row->getId(), serialize($row));

		$this->_redis->rpush('watcher_edit', $row->getId());
	}

	/**
	 * 从cache中取出对像
	 */
	public function get($id)
	{
		$obj = $this->_redis->get($id);

		if ($obj) {
			$obj = unserialize($obj);
			$obj->_cached = true;
			return $obj;
		}

		return null;
	}

	/**
	 * 从cache中删除对像
	 */
	public function del(row $row)
	{
		if ($row->_saved) {
			$this->_redis->set($row->getId(), serialize($row));
			$this->_redis->rpush('watcher_del', $row->getId());
		}
		else {
			$this->_redis->del($row->getId());
		}
	}
}
