<?php
/**
 * 账号信息数据访问层
 */
class Dao_RoleAction extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'role_action';
	protected $_tableName2 = 'actions';
	
	//获取role_id
	public function getRoleAction($role_id) {
		return DB::select('*')
				->from($this->_tableName)
				->join($this->_tableName2)
			    ->on("action_id", "=", "id")
				->where("role_id", "=", $role_id)
				->execute($this->_db)
				->as_array();
	}
}
