<?php
/**
 * 账号信息数据访问层
 */
class Dao_Action extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'actions';
	
	//获取role_id
	public function getAllAction() {
		return DB::select('*')
				->from($this->_tableName)
				->execute($this->_db)
				->as_array();
	}
}
