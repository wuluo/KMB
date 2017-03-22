<?php
/**
 * 帐号管理类
 */
class Account {

	const ROOT_USER = 1;
	const ROLE_SYSTEM_MANAGER = 1;
	const ROLE_RESOURCE_MANAGER = 2;
	const ROLE_NORMAL_USER = 3;

	protected static $_instances = array();

	public static function instance($accountId) {
		if(!isset(self::$_instances[$accountId])) {
			self::$_instances[$accountId]= new self($accountId);
		}
		return self::$_instances[$accountId];
	}

	public static function accountId($accountId) {
		return Account::instance($accountId)->getAccountId();
	}
	
	public static function name($accountId) {
		return Account::instance($accountId)->getName();
	}
	
	public static function givenName($accountId) {
		return Account::instance($accountId)->getGivenName();
	}
	
	public static function departments($accountId) {
		return Account::instance($accountId)->getDepartments();
	}
	
	public static function roles($accountId) {
		return Account::instance($accountId)->getRoles();
	}
	
	public static function privileges($accountId) {
		return Account::instance($accountId)->getPrivileges();
	}

	protected $_accountId = 0;

	protected $_name = '';

	protected $_given_name = '';

	protected $_departments = NULL;

	protected $_roles = NULL;

	protected $_privileges = NULL;

	public function __construct($accountId = 0) {
		$account = Business::factory('Account')->getAccountByAccountId($accountId);
		if(isset($account[0]['account_id'])) {
			$this->_accountId = $account[0]['account_id'];
		}
		if(isset($account[0]['name'])) {
			$this->_name = $account[0]['name'];
		}
		if(isset($account[0]['given_name'])) {
			$this->_given_name = $account[0]['given_name'];
		}
	}

	/**
	 * 账户id
	 * @return integer
	 */
	public function getAccountId() {
		return $this->_accountId;
	}

	/**
	 * 账户名
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * 返回账户姓名
	 * @return string
	 */
	public function getGivenName() {
		return $this->_given_name;
	}

	/**
	 * 返回账户部门
	 * @return object
	 */
	public function getDepartments() {
		if($this->_departments === NULL) {
			$this->_departments = Business::factory('Account')->getAccountDepartments($this->_accountId);
		}
		return $this->_departments;
	}
	
	/**
	 * 返回账户角色
	 * @return object
	 */
	public function getRoles() {
		if($this->_roles === NULL) {
			$this->_roles = Business::factory('Account')->getAccountRoles($this->_accountId);
		}
		return $this->_roles;
	}
	
	/**
	 * 返回账户权限
	 * @return object
	 */
	public function getPrivileges() {
		if($this->_privileges === NULL) {
			$this->_privileges = Business::factory('Account')->getPrivilegesByAccountId($this->_accountId);
		}
		return $this->_privileges;
	}
}
