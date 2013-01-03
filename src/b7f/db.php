<?php

namespace b7f;

use \PDO;

/**
 * 数据库连接类
 */
class db extends PDO
{
	/**
	 * 添加一条记录
	 *
	 * @param string $table 表名
	 * @param array  $feilds 数据 (格式: key=>val, key2=>val2)
	 * @return 失败返回false, 成功返影响记录条数
	 */
	public function insert($table, $feilds)
	{
		$keys = $vals = array();

		foreach ($feilds as $key => $val) {
			$keys[] = "`{$key}`";
			$vals[] = $this->quote($val);
		}

		$keys = implode(', ', $keys);
		$vals = implode(', ', $vals);

		$sql = "INSERT INTO {$table}\n({$keys})\nVALUES({$vals})";

		return $this->exec($sql);
	}

	/**
	 * 添加一批记录
	 *
	 * @param string $table  表名
	 * @param array  $feilds 字段 (格式: key1, key2)
	 * @param array  $values 数据 (格式: array(val1, val2), array(val1, val2))
	 * @return 失败返回false, 成功返影响记录条数
	 */
	public function inserts($table, $feilds, $values)
	{
		$vals = array();

		foreach ($values as $value) {
			$tmp = array();

			foreach ($value as $val) {
				$tmp[] = $this->quote($val);
			}

			$vals[] = '(' . implode(', ', $tmp) . ')';
		}

		$vals = implode(",\n", $vals);

		$sql = "INSERT INTO {$table}\n(`" .implode('`, `', $feilds). "`)\nVALUES {$vals}";

		return $this->exec($sql);
	}

	/**
	 * 修改一条数据
	 *
	 * @param string $table 表名 
	 * @param array  $feilds 数据 (格式: key=>val)
	 * @param array|string  $where
	 *     如果where为字串则会直接作为sql的where使用
	 *     如查where为数组 格式为: array(key=>val, key2=>val2)
	 *     其中如果val为数组为会解析成 key in (...)
	 *
	 * @return 失败返回false, 成功返影响记录条数
	 */
	public function update($table, $feilds, $where)
	{
		$set = array();

		foreach ($feilds as $key => $val) {
			$set[] = "`{$key}` = " . $this->quote($val);
		}

		$set = implode(', ', $set);

		$sql = "UPDATE {$table} SET {$set} WHERE " . $this->_where($where);

		return $this->exec($sql);
	}

	/**
	 * 删除数据
	 *
	 * @param string $table 表名 
	 * @param int    $limit 最大修改数量限制(可选)
	 * @param array|string  $where
	 *     如果where为字串则会直接作为sql的where使用
	 *     如查where为数组 格式为: array(key=>val, key2=>val2)
	 *     其中如果val为数组为会解析成 key in (...)
	 *
	 * @return 失败返回false, 成功返影响记录条数
	 */
	public function delete($table, $where, $limit=0)
	{
		$sql = "DELETE FROM {$table} WHERE " . $this->_where($where);

		if ($limit) {
			$sql .= "LIMIT {$limit}";
		}

		return $this->ecec($sql);
	}

	private function _where($where)
	{
		if (!is_array($where)) {
			return $where;
		}

		$_where = array();

		foreach ($where as $key => $val) {
			if (is_array($val)) {
				$_where[] = "`{$key}` IN (" . implode(', ', array_map(array($this, 'quote'), $val)) . ')';
			}
			else {
				$_where[] = "`{$key}` = " . $this->quote($val);
			}
		}

		return implode(' AND ', $_where);
	}


}
