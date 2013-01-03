<?php
namespace b7f;

/**
 * 请求级缓存
 */
class watcher
{
	protected $_rows = array();

	protected $_cache;

	public function __construct(cache $cache)
	{
		$this->_cache = $cache;
	}

	public function get($id)
	{
		if (isset($this->_rows[$id])) {
			return $this->_rows[$id];
		}

		return $this->_cache->get($id);
	}

	public function keep(row $row)
	{
		$row->_keep = $row->_keep | row::KEEP_PHP;

		$this->_rows[$row->getId()] = $row;
	}

	/**
	 * 将请求级缓存保存到缓存中
	 */
	public function save()
	{
		$cache = $this->_cache;

		foreach ($this->_rows as $row) {
			switch ($row->_state) {
			case row::STATE_ADD :
				$cache->add($row);
				break;

			case row::STATE_EDIT :
				$cache->edit($row);
				break;

			case row::STATE_DEL :
				if ($row->_cached) {
					$cache->del($row);
				}
				break;
			}
		}
	}

	public function __destruct()
	{
		$this->save();
	}
}
