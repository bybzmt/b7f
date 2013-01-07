<?php
namespace b7f;

/**
 * 数据映射器抽像类
 */
abstract class mapper
{
	/**
	 * 向数据库添加一条记录
	 */
	abstract public function insert($row);

	/**
	 * 向数据库修改一条记录
	 */
	abstract public function update($row);

	/**
	 * 向数据库删除一条记录
	 */
	abstract public function delete($row);

	/**
	 * 将数组映射成对像
	 */
	abstract public function doMap(array $row);
}
