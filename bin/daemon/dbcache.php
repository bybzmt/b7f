<?php

require __DIR__ . '/../../webroot/bootstrap.php';

$cache = get_db_cache();

$mappers = $adds = $dels = array();

while ($row = $cache->pop()) {
	switch ($row->_state) {
	case b7f\row::STATE_ADD :
		$mapper = $row->getMapper();

		$hash = spl_object_hash($mapper);

		if (!isset($mappers[$hash])) {
			$mappers[$hash] = $mapper;
		}

		$adds[$hash][] = $row;

		if (count($adds[$hash]) > 20) {
			do_inserts($mappers[$hash], $adds[$hash]);
			$adds[$hash] = array();
		}
		break;

	//更新无法合并,直接执行
	case b7f\row::STATE_EDIT :
		echo 'edit: '.$row->getId(),"\n";

		$row->getMapper()->update($row);

		$row->_state = null;
		$row->_keep &= ~b7f\row::KEEP_QUEUE;

		$cache->keep($row);
		break;

	case b7f\row::STATE_DEL :
		$mapper = $row->getMapper();

		$hash = spl_object_hash($mapper);

		if (!isset($mappers[$hash])) {
			$mappers[$hash] = $mapper;
		}

		$dels[$hash][] = $row;

		if (count($dels[$hash]) > 20) {
			do_dels($mappers[$hash], $dels[$hash]);
			$dels[$hash] = array();
		}

		break;
	}
}

foreach ($adds as $hash => $rows) {
	if ($rows) {
		do_inserts($mappers[$hash], $rows);
	}
}

foreach ($dels as $hash => $rows) {
	if ($rows) {
		do_dels($mappers[$hash], $rows);
	}
}

function do_inserts($mapper, $rows)
{
	$cache = get_db_cache();

	$mapper->inserts($rows);

	foreach ($rows as $row) {
		echo 'add: '.$row->getId(),"\n";

		$row->_keep |= b7f\row::KEEP_SAVED;
		$row->_keep &= ~b7f\row::KEEP_QUEUE;
		$row->_state = null;

		$cache->keep($row);
	}
}

function do_dels($mapper, $rows)
{
	$cache = get_db_cache();

	$mapper->deletes($rows);

	foreach ($rows as $row) {
		echo 'del: '.$row->getId(),"\n";

		$cache->del($row);
	}
}
