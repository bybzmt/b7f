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
}
