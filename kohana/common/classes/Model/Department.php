<?php
class Model_Department extends Model_BaseModel {
	
	/**
	 * 获取创建时间
	 * @param string $format
	 * @return string
	 */
	public function getCreateTime($format = NULL) {
		return $format ? date($format, $this->create_time) : $this->create_time;
	}
	
	/**
	 * 获取更新时间
	 * @param string $format
	 * @return string
	 */
	public function getUpdateTime($format = NULL) {
		return $format ? date($format, $this->update_time) : $this->update_time;
	}

	public function getName() {
		return HTML::chars($this->name);
	}

	/**
	 * 获取部门树深度
	 * @param string $format
	 * @return Ambigous <string, number>
	 */
	public function getDepth($format = NULL) {
		if($this->path === '') {
			$depth = 0;
		} else {
			$pathArr = explode(',', $this->path);
			$depth = count($pathArr);
		}
		return $format ? str_repeat($format, $depth) : $depth;
	}
	
	/**
	 * 获取部门下的所有角色
	 * @param Business_Client_Result $roles
	 * @return Ambigous <string, unknown>
	 */
	public function getRoles($roles){
		$departmentRoles = array();
		foreach($roles as $role) {
			if($this->department_id == $role->getDepartmentId()){
				$departmentRoles[$this->department_id][$role->getRoleId()] = $role;
			}
		}
		return isset($departmentRoles[$this->department_id])? $departmentRoles[$this->department_id] : '';
	}
}
