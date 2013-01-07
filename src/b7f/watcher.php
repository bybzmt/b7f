<?php
namespace b7f;

/**
 * 请求级缓存
 */
class watcher
{
	public $_rows = array();

	protected $_cache;

	public function __construct(cache $cache)
	{
		$this->_cache = $cache;
	}

	/**
	 * 得到一个对像
	 *
	 * @return null  未从缓存中找到
	 *         false 从缓存中确定该对像不存在
	 */
	public function get($id)
	{
		if (isset($this->_rows[$id])) {
			return $this->row[$id];
		}

		$row = $this->_cache->get($id);

		if ($row) {
			$this->_rows[$id] = $row;

			//当前行己被删除
			if ($row->_state == row::STATE_DEL) {
				return false;
			}
		}

		return $row;
	}

	/**
	 * 保持对像
	 *
	 * @param row  $row   要存的对象
	 * @param bool $saved 是否标记为己存储
	 */
	public function keep(row $row, $saved=false)
	{
		$row->_keep |= row::KEEP_PHP;

		if ($saved) {
			$row->_keep |= row::KEEP_SAVED;
		}

		$this->_rows[$row->getId()] = $row;
	}

	/**
	 * 标记对像不存在, 防止缓存击穿攻击
	 */
	public function makeNotExists($id)
	{
		$this->_cache->makeNotExists($id);
	}

	/**
	 * 将请求级缓存保存到缓存中
	 */
	public function save()
	{
		$cache = $this->_cache;

		foreach ($this->_rows as $key => $row) {
			if ($row->_state) {
				if (row::STATE_DEL && !($row->_keep & row::KEEP_SAVED)) {
					$cache->del($row);
				}
				else {
					$cache->keep($row, true);
				}
			}
			else if (!($row->_keep & row::KEEP_CACHED)){
				$cache->keep($row);
			}
		}
	}

	public function __destruct()
	{
		$this->save();
	}
}
