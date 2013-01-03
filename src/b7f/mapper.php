<?php
namespace b7f;

abstract class mapper
{
	abstract public function getWatcher();
	abstract public function getMapperName();
	abstract public function doMap(array $row);

	public function save(row $obj)
	{
		if ($obj->_keep & row::KEEP_SAVED) {
			$obj->_state = row::STATE_EDIT;
		}
		else {
			$obj->_state = row::STATE_ADD;
		}

		if (!($obj->_keep & row::KEEP_PHP)) {
			$this->getWatcher()->keep($obj);
		}
	}

	public function del(row $obj)
	{
		$obj->_state = row::STATE_DEL;
	}


	protected function _doMap(array $row)
	{
		$obj = $this->doMap($row);
		$obj->_keep |= row::KEEP_SAVED;

		$this->getWatcher()->keep($obj);

		return $obj;
	}

	protected function _find($id)
	{
		return $this->getWatcher()->get($id);
	}
}
