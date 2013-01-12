<?php
namespace b7f;

/**
 * 数据映射器抽像类
 */
abstract class mapper
{
	/**
	 * 向数据库添加一批记录
	 */
	abstract public function inserts(array $row);

	/**
	 * 向数据库修改一条记录
	 */
	abstract public function update($row);

	/**
	 * 向数据库删除一批记录
	 */
	abstract public function deletes(array $row);

	/**
	 * 将数组映射成对像
	 */
	abstract public function doMap(array $row);
}
