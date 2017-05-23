<?php

class Dao_Log extends Dao {

	protected $_tableName = 'log';
	protected $_primaryKey = 'log_id';

	/**
	 * 获取日志列表
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogs($number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by('log_id', 'DESC');
		if ($number) {
			$select->limit($number);
		}
		if ($offset) {
			$select->offset($offset);
		}
		return $select->as_object('Model_Log')->execute($this->_db);
	}

	/**
	 * 根据关键字获取日志列表
	 * @param string $keyword
	 * @param integer $offset
	 * @param integer $number
	 * @return array
	 */
	public function getLogsByKeyword($keyword, $search, $number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by('log_id', 'DESC');
		if ($keyword) {
			$select->where('log_id', '=', $keyword);
			$select->or_where('account_name', '=', $keyword);
			$select->or_where('controller', '=', $keyword);
			$select->or_where($search, 'LIKE', '%' . $keyword . '%');
		}
		if ($number) {
			$select->limit($number);
		}
		if ($offset) {
			$select->offset($offset);
		}
		return $select->as_object('Model_Log')->execute($this->_db);
	}

	/**
	 * 获取日志数量
	 * @return integer
	 */
	public function countLog() {
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
	public function countLogByKeyword($keyword, $search) {
		$select = DB::select(DB::expr('COUNT(*) AS recordCount'))
			->from($this->_tableName);
		if ($keyword) {
			$select->where('log_id', '=', $keyword);
			$select->or_where('account_name', '=', $keyword);
			$select->or_where('controller', '=', $keyword);
			$select->or_where($search, 'LIKE', '%' . $keyword . '%');
		}
		return $select->execute($this->_db)->get('recordCount');
	}
}
