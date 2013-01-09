<?php

namespace b7f;

abstract class row
{
	/**
	 * 当前状态,添加, 编辑, 删除
	 */
	const STATE_ADD  = 1;
	const STATE_EDIT = 2;
	const STATE_DEL  = 3;

	/**
	 * 保持在数库,cache,php中
	 */
	const KEEP_SAVED  = 1;
	const KEEP_CACHED = 2;
	const KEEP_PHP    = 4;

	/**
	 * 缓存数据己变动
	 */
	const KEEP_CHANGE = 8;

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
