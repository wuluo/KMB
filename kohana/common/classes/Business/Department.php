<?php
class Business_Department extends Business {


	/**
	 * 添加部门
	 * @param array $values
	 * @return array
	 */
	public function create(array $values) {
		$fields = array(
			'name' => '',
			'parent_id' => '',
			'path' => '',
			'sequence' => '0',
			'create_time' => time(),
			'update_time' => time(),
		);
		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;
		$name = Arr::get($values, 'name', '');
		$parentId = Arr::get($values, 'parent_id', 0);
		$errors = array();
		if(!$name) {
			$errors[] = '名称不能为空！';
		}
		if($errors) {
			throw new Business_Exception(implode(' ', $errors));
		}

		if($parentId) {
			$parentDepartment = Dao::factory('Department')->getDepartmentByDepartmentId($parentId)->current();
			if (empty($parentDepartment->path)) {
				$values['path'] = $parentId;
			} else {
				$values['path'] = $parentDepartment->path .','.$parentId;
			}
		} else {
			$values['path'] = '';
		}
		$departments = Dao::factory('Department')->getDepartmentsByParentId($parentId, 1)->current();
		if($departments) {
			$values['sequence'] = $departments->sequence + 1;
		} else {
			$values['sequence'] = 1;
		}
		return Dao::factory('Department')->insert($values);
	}

	/**
	 * 更新部门
	 * @param array $values
	 * @return array
	 */
	public function update(array $values = array()) {
		$departmentId = Arr::get($values, 'department_id', '');
		$fields = array(
			'name' => '',
			'parent_id' => '',
			'path' => '',
			'sequence' => '0',
			'update_time' => time(),
		);
		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;
		
		$name = Arr::get($values, 'name', '');
		$parentId = Arr::get($values, 'parent_id', 0);
		
		$errors = array();
		if (!$name) {
			$errors[] = '名称不能为空！';
		}
		if (!$parentId) {
			$errors[] = '上级部门不能为空！';
		}
		if ($departmentId == $parentId) {
			$errors[] = '上级部门不能为自己！';
		}
		if ($errors) {
			throw new Business_Exception(implode(' ', $errors));
		}
		if ($parentId) {
			$parentDepartment = Dao::factory('Department')->getDepartmentByDepartmentId($parentId)->current();
			if (empty($parentDepartment->path)) {
				$values['path'] = $parentId;
			} else {
				$values['path'] = $parentDepartment->path .','.$parentId;
			}
		} else {
			$values['path'] = '';
		}
		$departments = Dao::factory('Department')->getDepartmentsByParentId($parentId, 1)->current();
		if($departments) {
			$values['sequence'] = $departments->sequence + 1;
		} else {
			$values['sequence'] = 1;
		}
		unset($values['department_id']);
		return Dao::factory('Department')->updateByDepartmentId($departmentId, $values);
	}


	/**
	 * 获取部门列表
	 * @param integer $number
	 * @param integer $offset
	 * @return object
	 */
	public function getDepartments($number = 0, $offset = 0) {
		$departments = Dao::factory('Department')->getDepartments($number, $offset);
		return $departments;
	}

	/**
	 * 获取一个部门
	 * @param integer $departmentId
	 * @return array
	 */
	public function getDepartmentByDepartmentId($departmentId = 0) {
		return Dao::factory('Department')->getDepartmentByDepartmentId($departmentId);
	}

	/**
	 * 获取一组部门
	 * @param array $departmentIds
	 * @return array
	 */
	public function getDepartmentsByDepartmentIds(array $departmentIds = array()) {
		return Dao::factory('Department')->getDepartmentsByDepartmentIds($departmentIds);
	}

	/**
	 * 删除一个部门
	 * @param integer $departmentId
	 * @return boolean
	 */
	public function delete($departmentId = 0) {
		$departments = Dao::factory('Department')->getDepartmentsByParentId($departmentId, 1);
		if($departments->count()) {
			throw new Business_Exception('有下级部门存在，不能删除！');
		}
		$departmentAccounts = Dao::factory('Account_Department')->getAccountDepartmentByDepartmentId($departmentId);
		if($departmentAccounts->count()) {
			throw new Business_Exception('部门下有成员，不能删除！');
		}

		return Dao::factory('Department')->delete($departmentId);
	}

	/**
	 * 获取当前部门的所有帐号
	 * @param integer $accountId
	 * @return array
	 */
	public function getAccountsByDepartmentId($departmentId = 0) {
		//取得帐号的所有角色
		$accountIds = array();
		$departmentAccounts = Dao::factory('Account_Department')->getAccountDepartmentByDepartmentId($departmentId);
		foreach($departmentAccounts as $departmentAccount) {
			$accountIds[] = $departmentAccount->account_id;
		}
		if(!$accountIds) {
			return array();
		}
		//返回所有帐号
		return Dao::factory('Account')->getAccountsByAccountIds($accountIds);
	}

	/**
	 * 从部门中删除一个帐号
	 * @param integer $departmentId
	 * @param integer $accountId
	 * @return boolean
	 */
	public function deleteAccount($departmentId, $accountId) {
		return Dao::factory('Account_Department')->deleteAccount($departmentId, $accountId);
	}
	
	/**
	 * 按名称获取一个部门
	 * @param string $name
	 * @return array
	 */
	public function getDepartmentByName($name) {
		return Dao::factory('Department')->getDepartmentByName($name);
	}

	/**
	 * 处理部门分类
	 * @param  object $departments
	 * @return object
	 */
	public function recursive($departments) {
		
		foreach ($departments as $key => $value) {
			if ($value->path == '') {
				$departments[$key]->level = 1;
			} else {
				$substrCount = substr_count($value->path, ',');
				$departments[$key]->level = 2+$substrCount;
			}
			
		}
		foreach ($departments as $keys => $values) {
			if ($values->parent_id == 0) {
				$departmentsGroup[$values->department_id] = $values;
			} else {
				$departmentsGroup[$values->path.','.$values->department_id] = $values;
			}
		}
		ksort($departmentsGroup);

		return $departmentsGroup;
	}

	/**
	 * 获取部门列表
	 * @param integer $number
	 * @param integer $offset
	 * @return array
	 */
	public function getDepartmentsArray($number = 0, $offset = 0) {
		$departments = Dao::factory('Department')->getDepartmentsArray($number, $offset);
		return $departments;
	}
}
