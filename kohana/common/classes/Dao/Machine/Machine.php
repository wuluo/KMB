<?php

/**
 * desc
 * @package default
 * @author  quanhengzhe
 * @date:   2017/5/10
 * @time:   11:18
 */
class Dao_Machine_Machine extends Dao {
	protected $_db = 'system';	
	protected $_tableName = 'machine';
	protected $_modelName = 'Model_Machine_Machine';

	public function searchMachineByInIp($condition = '', $inIp, $fields = '*', $is_delete = '') {
		$select = DB::select($fields)
			->from($this->_tableName)
			->where($condition, '=', $inIp);
			if (!$is_delete) {
				$select->and_where('is_delete', '=', 0);
			}
			
			$select->limit(1);
		return $select->as_object($this->_modelName)->execute($this->_db);
	}

	public function addNewMachine($data) {
		return $insert = DB::insert($this->_tableName)
			->columns(array_keys($data))
			->values(array_values($data))
			->execute($this->_db);
	}

	public function modifyMachineData($condition, $modifyData, $conditional) {
		return $update = DB::update($this->_tableName)
			->set($modifyData)
			->where($condition, '=', $conditional)
			->execute($this->_db);
	}

	public function machineList($condition, $logic, $number, $offset, $orderBy, $direction) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by($orderBy, $direction);
		if ($number) {
			$select->limit($number);
		}
		if ($offset) {
			$select->offset($offset);
		}
		foreach ($condition as $k => $v) {
			if ($logic == "and") {
				$select->and_where($k, array_shift($v), array_shift($v));
			} else {
				$select->or_where($k, array_shift($v), array_shift($v));
			}
		}
		$select->and_where('is_delete', '=', 0);
		return $select->as_object($this->_modelName)->execute($this->_db);
	}

	public function getConditionCount($condition, $logic) {
		$select = DB::select("count(*) as count ")
			->from($this->_tableName);
		foreach ($condition as $k => $v) {
			if ($logic == "or") {
				$select->or_where($k, array_shift($v), array_shift($v));
			} else {
				$select->and_where($k, array_shift($v), array_shift($v));
			}
		}
		$select->and_where('is_delete', '=', 0);
		return $select->execute($this->_db)->get("count");
	}

	public  function getMachines() {
		return DB::select('*')
			->from($this->_tableName)
			->where('is_delete', '=', 0)
			->and_where('is_available', '=', 1)
			->and_where('service', '!=', 'default')
			->and_where('machine_group', '!=', 'default')
			->execute($this->_db);
	}
}