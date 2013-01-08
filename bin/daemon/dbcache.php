<?php

require __DIR__ . '/../../webroot/bootstrap.php';

$cache = get_db_cache();

while ($row = $cache->pop()) {
	switch ($row->_state) {
	case b7f\row::STATE_ADD :
		$row->getMapper()->insert($row);

		$row->_keep |= b7f\row::KEEP_SAVED;
		$row->_state = null;

		$cache->keep($row);
		break;
	case b7f\row::STATE_EDIT :
		$row->getMapper()->update($row);

		$row->_state = null;

		$cache->keep($row);
		break;
	case b7f\row::STATE_DEL :
		$row->getMapper()->delete($row);

		$cache->del($row);
		break;
	}
}
