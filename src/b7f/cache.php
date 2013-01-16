<?php
namespace b7f;

use \redis;

/**
 * 数据库缓存
 */
class cache
{
	//redis连接
	protected $_redis;

	//缓存前缓
	protected $_prefix = 'db_';

	//数据更改队列
	protected $_queue = 'db_chang_queue';

	public function __construct(redis $redis)
	{
		$this->_redis = $redis;
	}

	/**
	 * 保存数据
	 *
	 * @param row  $row   数据
	 * @param bool $chang 数据是否己改变
	 */
	public function keep(row $row)
	{
		$chang = $row->_keep & row::KEEP_CHANGE;
		$queue = $row->_keep & row::KEEP_QUEUE;

		$id = $row->getId();

		//去己变动标记
		$row->_keep &= ~row::KEEP_CHANGE;

		//标记为以缓存
		$row->_keep |= row::KEEP_CACHED;

		if ($chang) {
			//标记为以队列
			$row->_keep |= row::KEEP_QUEUE;
		}

		//10小时后过期 (过期时间在10分钟内抖动,防止出现缓存集休失效)
		$this->_redis->setex("{$this->_prefix}{$id}", 36000+mt_rand(0, 600), serialize($row));

		if ($chang && !$queue) {
			$this->_redis->rpush($this->_queue, $id);
		}
	}

	/**
	 * 标记对像不存在, 防止缓存击穿攻击
	 */
	public function makeNotExists($id)
	{
		//随机5到10分钟的缓存,防止出现缓存集休失效
		$this->_redis->setex("{$this->_prefix}{$id}", mt_rand(300, 600), serialize(false));
	}
	
	/**
	 * 从cache中取出对像
	 *
	 * @return null  未从缓存中找到
	 *         false 从缓存中确定该对像不存在
	 */
	public function get($id)
	{
		$obj = $this->_redis->get("{$this->_prefix}{$id}");

		if ($obj) {
			$obj = unserialize($obj);

			if ($obj) {
				$obj->_keep |= row::KEEP_CACHED;
			}

			return $obj;
		}

		return null;
	}

	/**
	 * 从cache中删除对像
	 */
	public function del(row $row)
	{
		$this->_redis->del($row->getId());
		$row->_keep &= ~row::KEEP_CACHED;
	}

	/**
	 * 从队列中弹出一个需要进行的操作
	 */
	public function pop()
	{
		while (true) {
			$id = $this->_redis->lpop($this->_queue);

			if (!$id) {
				return null;
			}

			$row = $this->get($id);

			if ($row) {
				return $row;
			}
		}
	}
}
