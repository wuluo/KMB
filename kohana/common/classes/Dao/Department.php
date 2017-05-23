<?php
class Dao_Department extends Dao {

	protected $_tableName = 'department';

	protected $_primaryKey = 'department_id';

	/**
	 * 递归获取部门
	 * @param integer $departmentId
	 * @param array $departments
	 * @return array
	 */
	public static function recursive($parentId = 0, Array $departments) {
		static $return = array();
		static $depth = 0;

		if(!$departments) {
			return array();
		}

		$depth++;

		foreach($departments as $department) {
			if($parentId == $department['parent_id']) {
				$return[$department['department_id']] = $department;
				$return[$department['department_id']]['depth'] = $depth;
				self::recursive($department['department_id'], $departments);
			}
		}

		$depth--;
		return $return;
	}
	
	/**
	 * 插入部门
	 * @param array $values
	 * @return Integer
	 */
	public function insert(array $values) {
		return DB::insert($this->_tableName)
			->columns(array_keys($values))
			->values(array_values($values))
			->execute($this->_db);
	}

	/**
	 * 更新部门
	 * @param integer $departmentId
	 * @param array $values
	 * @return integer
	 */
	public function updateByDepartmentId($departmentId, array $values = array()) {
		if($departmentId <= 0 || !$values) {
			return 0;
		}
		return DB::update($this->_tableName)
			->set($values)
			->where('department_id', '=', $departmentId)
			->execute($this->_db);
	}

	/**
	 * 获取部门列表
	 * @param integer $number
	 * @param integer $offset
	 * @return object
	 */
	public function getDepartments($number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by('department_id', 'ASC');
		if($number) {
			$select->limit($number);
		}
		if($offset) {
			$select->offset($offset);
		}
		$departments = $select->as_object('Model_Department')->execute($this->_db);
		return $departments;
	}
	
	/**
	 * 获取一个部门
	 * @param integer $departmentId
	 * @return array
	 */
	public function getDepartmentByDepartmentId($departmentId) {
		if($departmentId <= 0){
			return 0;
		}
		return DB::select('*')
				->from($this->_tableName)
				->where('department_id', '=', $departmentId)
				->as_object('Model_Department')
				->execute($this->_db);
	}

	/**
	 * 获取一组部门
	 * @param array $departmentIds
	 * @return array
	 */
	public function getDepartmentsByDepartmentIds(array $departmentIds = array()) {
		if(!$departmentIds) {
			return array();
		}
		return DB::select('*')
			->from($this->_tableName)
			->where('department_id', 'IN', $departmentIds)
			->as_object('Model_Department')
			->execute($this->_db);
	}

	/**
	 * 根据父ID获取部门列表
	 * @param integer $parentId
	 * @return array
	 */
	public function getDepartmentsByParentId($parentId = 0, $number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->where('parent_id', '=', $parentId)
			->order_by('sequence', 'DESC');
		if($number) {
			$select->limit($number);
		}
		if($offset) {
			$select->offset($offset);
		}
		return $select->as_object('Model_Department')->execute($this->_db);
	}

	/**
	 * 根据名字获取部门信息
	 * @param string $name
	 * @return array
	 */
	public function getDepartmentByName($name) {
		return DB::select('*')
			->from($this->_tableName)
			->where('name', '=', $name)
			->as_object('Model_Department')
			->execute($this->_db);
	}

	/**
	 * 删除部门
	 * @param integer $departmentId
	 * @return integer
	 */
	public function delete($departmentId) {
		if($departmentId <= 0){
			return 0;
		}
		return DB::delete($this->_tableName)
			->where('department_id', '=', $departmentId)
			->execute($this->_db);
	}

	/**
	 * 获取部门列表
	 * @param integer $number
	 * @param integer $offset
	 * @return array
	 */
	public function getDepartmentsArray($number = 0, $offset = 0) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by('department_id', 'ASC');
		if($number) {
			$select->limit($number);
		}
		if($offset) {
			$select->offset($offset);
		}
		$departments = $select->execute($this->_db)->as_array();
		
		return self::recursive(0,$departments);
	}

	/**
	 * 递归获取部门
	 * @param integer $departmentId
	 * @param array $departments
	 * @return object
	 */
	public static function recursives($parentId = 0, $departments) {
		static $return = array();
		static $depth = 0;

		if(!$departments) {
			return array();
		}

		$depth++;
		foreach($departments as $department) {
			if ($parentId == $department->parent_id) {

				$return[$department->department_id] = $department;
				$return[$department->department_id]->depth = $depth;
				self::recursives($department->department_id, $departments);
			}
		}

		$depth--;
		return $return;
	}
}
