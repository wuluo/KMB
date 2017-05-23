<?php

class Dao_Crash extends Dao {

	protected $_db = 'default';
	protected $_tableName = 'log_crash';
	protected $_primaryKey = 'log_crash_id';

	/**
	 * 获取异常日志列表
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogCrashes($number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by($this->_primaryKey, 'DESC');
		if ($number) {
			$select->limit($number);
		}
		if ($offset) {
			$select->offset($offset);
		}
		return $select->as_object('Model_Crash')->execute($this->_db);
	}

	/**
	 * 根据关键字获取日志列表
	 * @param string $keyword
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogCrashesByKeyword($keyword, $number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by($this->_primaryKey, 'DESC');
		if ($keyword) {
			$select->where('file', 'LIKE', '%' . $keyword . '%');
			$select->or_where('message', 'LIKE', '%' . $keyword . '%');
			$select->or_where('ip', 'LIKE', '%' . $keyword . '%');
			$select->or_where('hostname', 'LIKE', '%' . $keyword . '%');
		}
		if ($number) {
			$select->limit($number);
		}
		if ($offset) {
			$select->offset($offset);
		}
		return $select->as_object('Model_Crash')->execute($this->_db);
	}

	/**
	 * 获取日志数量
	 * @return integer
	 */
	public function countLogCrash() {
		return DB::select(DB::expr('COUNT(*) AS recordCount'))
			->from($this->_tableName)
			->execute($this->_db)
			->get('recordCount');
	}


	/**
	 * 根据关键字获取日志数量
	 * @param string $keyword
	 * @return integer
	 */
	public function countLogCrashByKeyword($keyword) {
		$select = DB::select(DB::expr('COUNT(*) AS recordCount'))
			->from($this->_tableName);
		if ($keyword) {
			$select->where('file', 'LIKE', '%' . $keyword . '%');
			$select->or_where('message', 'LIKE', '%' . $keyword . '%');
			$select->or_where('ip', 'LIKE', '%' . $keyword . '%');
			$select->or_where('hostname', 'LIKE', '%' . $keyword . '%');
		}
		return $select->execute($this->_db)->get('recordCount');
	}

	public function deleteBefore($start,$end){
		$delete = DB::delete($this->_tableName)
			->where("create_time","between",[$start,$end]);
		return $delete->execute($this->_db);
	}
}
