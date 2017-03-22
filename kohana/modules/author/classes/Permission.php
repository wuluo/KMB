<?php
/**
 * 权限验证类
 */
class Permission {

	protected static $_instance = NULL;

	public static function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * 是否可读
	 * @param integer $accountId
	 * @param integer $mask
	 * @return boolean
	 */
	public static function readable($accountId, $mask = 0774) {
		$account = Account::instance($accountId);
		return Permission::instance()
					->account($account)
					->mask($mask)
					->execute()
					->isReadable();
	}

	/**
	 * 是否可编辑
	 * @param integer $accountId
	 * @param integer $mask
	 * @return boolean
	 */
	public static function editable($accountId, $mask = 0774) {
		$account = Account::instance($accountId);
		return Permission::instance()
					->account($account)
					->mask($mask)
					->execute()
					->isEditable();
	}

	/**
	 * 是否可删除
	 * @param integer $accountId
	 * @param integer $mask
	 * @return boolean
	 */
	public static function deletable($accountId, $mask = 0774) {
		$account = Account::instance($accountId);
		return Permission::instance()
					->account($account)
					->mask($mask)
					->execute()
					->isDeletable();
	}

	protected $_account = NULL;

	protected $_mask = 0000;

	protected $_ownerReadableMask = 0400;
	protected $_ownerEditableMask = 0200;
	protected $_ownerDeletableMask = 0100;

	protected $_groupReadableMask = 0040;
	protected $_groupEditableMask = 0020;
	protected $_groupDeletableMask = 0010;

	protected $_otherReadableMask = 0004;
	protected $_otherEditableMask = 0002;
	protected $_otherDeletableMask = 0001;

	protected $_readable = FALSE;

	protected $_editable = FALSE;

	protected $_deletable = FALSE;

	/**
	 * 设置帐号
	 * @param object $account
	 * @return object
	 */
	public function account(Account $account = NULL) {
		$this->_account = $account;
		return $this;
	}

	/**
	 * 设置掩码
	 * @param string $mask
	 * @return object
	 */
	public function mask($mask = 0000) {
		$this->_mask = $mask;
		return $this;
	}

	/**
	 * 执行
	 * @return object
	 */
	public function execute() {
		//@tudo 系统管理员和资源管理员操作权限划分
		if($this->_isRoot() || $this->_isResourceManager()) {
			$this->_readable = TRUE;
			$this->_editable = TRUE;
			$this->_deletable = TRUE;
		} elseif($this->_isOwner()) {
			$this->_readable = ($this->_mask & $this->_ownerReadableMask) ? TRUE : FALSE;
			$this->_editable = ($this->_mask & $this->_ownerEditableMask) ? TRUE : FALSE;
			$this->_deletable = ($this->_mask & $this->_ownerDeletableMask) ? TRUE : FALSE;
		} elseif($this->_isGroup()) {
			$this->_readable = ($this->_mask & $this->_groupReadableMask) ? TRUE : FALSE;
			$this->_editable = ($this->_mask & $this->_groupEditableMask) ? TRUE : FALSE;
			$this->_deletable = ($this->_mask & $this->_groupDeletableMask) ? TRUE : FALSE;
		} else {
			$this->_readable = ($this->_mask & $this->_otherReadableMask) ? TRUE : FALSE;
			$this->_editable = ($this->_mask & $this->_otherEditableMask) ? TRUE : FALSE;
			$this->_deletable = ($this->_mask & $this->_otherDeletableMask) ? TRUE : FALSE;
		}

		return $this;
	}

	/**
	 * 是否可读
	 * @return boolean
	 */
	public function isReadable() {
		return $this->_readable;
	}

	/**
	 * 是否可编辑
	 * @return boolean
	 */
	public function isEditable() {
		return $this->_editable;
	}

	/**
	 * 是否可删除
	 * @return boolean
	 */
	public function isDeletable() {
		return $this->_deletable;
	}

	/**
	 * 判断是否是root用户
	 * @return boolean
	 */
	protected function _isRoot() {
		if(Author::accountId() == Account::ROOT_USER) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 判断是否是系统管理员
	 * @return boolean
	 */
	protected function _isSystemManager() {
		$roles = Author::roles();
		foreach($roles as $role) {
			$roleIds[] = $role->getRoleId();
		}
		if(in_array(Account::ROLE_SYSTEM_MANAGER, $roleIds)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 判断是否是资源管理员
	 * @return boolean
	 */
	protected function _isResourceManager() {
		$roles = Author::roles();
		foreach($roles as $role) {
			$roleIds[] = $role->getRoleId();
		}
		if(in_array(Account::ROLE_RESOURCE_MANAGER, $roleIds)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 判断是否是自己
	 * @return boolean
	 */
	protected function _isOwner() {
		if(Author::accountId() == $this->_account->getAccountId()) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 判断是否是同一部门成员
	 * @return boolean
	 */
	protected function _isGroup() {
		$departments = $this->_account->getDepartments();
		$authorDepartments = Author::departments();

		$departmentIds = array();
		foreach($departments as $department) {
			$departmentIds[]= $department->getDepartmentId();
		}
		$authorDepartmentIds = array();
		foreach($authorDepartments as $authorDepartment) {
			$authorDepartmentIds[]= $authorDepartment->getDepartmentId();
		}
		if(empty($departmentIds) || empty($authorDepartmentIds)) {
			return FALSE;
		}
		if(!array_diff($departmentIds, $authorDepartmentIds)) {
			return TRUE;
		}
		return FALSE;
	}
}
