<?php
class Model_Account extends Model_BaseModel {
	
	const STATUS_DELETED = 1;
	
	const STATUS_NORMAL = 0;

	public function getCreateTime($format = NULL) {
		return $format ? date($format, $this->create_time) : $this->create_time;
	}

	public function getUpdateTime($format = NULL) {
		return $format ? date($format, $this->update_time) : $this->update_time;
	}
	
	public function getStatus($text = false) {
		if(!$text) {
			return $this->is_delete;
		}
		switch($this->is_delete) {
			case self::STATUS_NORMAL:
				return '正常';
				break;
			case self::STATUS_DELETED:
				return '屏蔽';
				break;
			default:
				return $this->is_delete;
		}
	}

	public function getGivenName() {
		return HTML::chars($this->given_name);
	}

	public function getName() {
		return HTML::chars($this->name);
	}

	public function getEmail() {
		return HTML::chars($this->email);
	}

	/**
	 * 找到帐号部门
	 * @param string $column
	 * @return array
	 */
	public function getDepartments($column = NULL) {
		if(!$this->departments) {
			return NULL;
		}
		if($column) {
			$values = array();
			foreach($this->departments as $department) {
				$values[] = $department->$column;
			}
			return $values;
		}
		return $this->departments;
	}
	
	/**
	 * 找到帐号角色
	 * @param string $column
	 * @return array
	 */
	public function getRoles($column = NULL) {
		if(!$this->roles) {
			return NULL;
		}
		if($column) {
			$values = array();
			foreach($this->roles as $role) {
				$values[] = $role->$column;
			}
			return $values;
		}
		return $this->roles;
	}
}
