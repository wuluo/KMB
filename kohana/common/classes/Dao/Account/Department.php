<?php
class Dao_Account_Department extends Dao {

	protected $_tableName = 'account_department';

	protected $_primaryKey = 'account_department_id';

	/**
	 * 获取一个部门的帐号关系
	 * @param integer $departmentId
	 * @return array
	 */
	public function getAccountDepartmentByDepartmentId($departmentId) {
		return DB::select('*')
			->from($this->_tableName)
			->where('department_id', '=', $departmentId)
			->as_object('Model_Account_Department')
			->execute($this->_db);
	}

	/**
	 * 获取一个帐号的部门关系
	 * @param integer $accountId
	 * @return array
	 */
	public function getAccountDepartmentsByAccountId($accountId) {
		return DB::select('*')
			->from($this->_tableName)
			->where('account_id', '=', $accountId)
			->as_object('Model_Account_Department')
			->execute($this->_db);
	}

	/**
	 * 获取一组帐号的部门关系
	 * @param array $accountIds
	 * @return array
	 */
	public function getAccountDepartmentsByAccountIds(array $accountIds) {
		if(!$accountIds) {
			return array();
		}
		return DB::select('*')
			->from($this->_tableName)
			->where('account_id', 'IN', $accountIds)
			->as_object('Model_Account_Department')
			->execute($this->_db);
	}
	
	/**
	 * 从部门中删除一个帐号
	 * @param integer $departmentId
	 * @param integer $accountId
	 * @return integer
	 */
	public function deleteAccount($departmentId, $accountId) {
		return DB::delete($this->_tableName)
			->where('department_id', '=', $departmentId)
			->and_where('account_id', '=', $accountId)
			->execute($this->_db);
	}
	
	/**
	 * 删除一个账号的部门关系
	 * @param integer $accountId
	 * @return integer
	 */
	public function deleteByAccountId($accountId) {
		return DB::delete($this->_tableName)
			->where('account_id', '=', $accountId)
			->execute($this->_db);
	}
	
	/**
	 * 生成账号与部门关系
	 * @param integer $accountId
	 * @param array $departmentIds
	 * @return integer
	 */
	public function generate($accountId, array $departmentIds, $time) {
		$columns = array('account_id', 'department_id', 'create_time');
		$insert = DB::insert($this->_tableName)
			->columns($columns);
		foreach($departmentIds as $departmentId) {
			$values = array($accountId, $departmentId, $time);
			$insert->values($values);
		}
		return $insert->execute($this->_db);
	}

	/**
	 * 插入帐号部门关系
	 * @param array $values
	 * @return array
	 */
	public function insert(array $values) {
		if(!$values) {
			return array(0, 0);
		}
		return DB::insert($this->_tableName)
			->columns(array_keys($values))
			->values(array_values($values))
			->execute($this->_db);
	}
}
