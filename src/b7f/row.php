<?php

namespace b7f;

abstract class row implements \ArrayAccess
{
	/**
	 * 当前状态,添加, 编辑, 删除
	 */
	const STATE_ADD  = 1;
	const STATE_EDIT = 2;
	const STATE_DEL  = 3;

	/**
	 * 保持在数库,cache,php,队列中
	 */
	const KEEP_SAVED  = 1;
	const KEEP_CACHED = 2;
	const KEEP_PHP    = 4;
	const KEEP_QUEUE  = 8;

	/**
	 * 缓存数据己变动
	 */
	const KEEP_CHANGE = 16;

	/**
	 * 是否存储在cache中
	 */
	public $_keep;

	/**
	 * 当前状态
	 */
	public $_state;

	/**
	 * 得到全局id
	 */
	abstract public function getId();

	/**
	 * 得映射器
	 */
	abstract public function getMapper();

	/**
	 * 得到观察者
	 */
	abstract public function getWatcher();

	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}

	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	public function offsetSet($offset, $value)
	{
		throw new exception('不充许进行此操作');
	}

	public function offsetUnset($offset)
	{
		throw new exception('不充许进行此操作');
	}

	public function __set($key, $val)
	{
		throw new exception('不充许进行此操作');
	}

	/**
	 * 保存
	 */
	public function save()
	{
		if ($this->_keep & self::KEEP_SAVED) {
			$this->_state = self::STATE_EDIT;
		}
		else {
			$this->_state = self::STATE_ADD;
		}

		$this->_keep |= self::KEEP_CHANGE;

		if (!($this->_keep & self::KEEP_PHP)) {
			$this->getWatcher()->keep($this);
		}
	}

	/**
	 * 删除
	 */
	public function del()
	{
		$this->_state = self::STATE_DEL;
	}
}
