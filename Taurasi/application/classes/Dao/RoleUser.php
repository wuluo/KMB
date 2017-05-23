<?php
/**
 * 账号信息数据访问层
 */
class Dao_RoleUser extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'role_user';
	
	//获取role_id
	public function getRoleId($userId) {
		return DB::select('*')
				->from($this->_tableName)
				->where('user_id', '=', $userId)
				->as_object('Model_RoleUser')
				->execute($this->_db);
	}
}
