<?php

/**
 * 用户登录管理类
 */
class Author {

	static public $default = 'author';
	protected static $_instance = NULL;

	public static function instance() {
		if (self::$_instance === NULL) {
			$config = Kohana::$config->load(self::$default)->as_array();
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}

	public static function accountId() {
		return Author::instance()->getAccountId();
	}

	public static function name() {
		return Author::instance()->getName();
	}

	public static function givenName() {
		return Author::instance()->getGivenName();
	}

	public static function departments() {
		return Author::instance()->getDepartments();
	}

	public static function roles() {
		return Author::instance()->getRoles();
	}

	public static function privileges() {
		return Author::instance()->getPrivileges();
	}

	protected $_accountId = 0;
	protected $_name = '';
	protected $_given_name = '';
	protected $_departments = NULL;
	protected $_roles = NULL;
	protected $_privileges = NULL;
	protected $_config = array();
	protected $_host = 'http://gvs.dev.gomeplus.com';

	public function __construct(array $config = NULL) {
		if ($config === NULL) {
			throw new Author_Exception('缺少配置文件');
		}
		$this->_config = $config;
		$this->_host = Kohana::$config->load('default.host');
		$author = Session::instance()->get('author', array());
		if (isset($author['account_id'])) {
			$this->_accountId = $author['account_id'];
		}
		if (isset($author['name'])) {
			$this->_name = $author['name'];
		}
		if (isset($author['given_name'])) {
			$this->_given_name = $author['given_name'];
		}
	}

	/**
	 * 判断是否已经登录
	 * 
	 * $encrypt = new Encrypt($key, $mode, $cipher);
	 * $passport = $encrypt->encode(name@md5(ua.ip.password));
	 * 
	 * @return boolean
	 */
	public function isLogin() {
		$passport = Cookie::get($this->_config['passport'], NULL);

		if ($passport === NULL) {
			return FALSE;
		}

		$encrypt = new Encrypt($this->_config['key'], $this->_config['mode'], $this->_config['cipher']);
		$text = $encrypt->decode($passport);
		$pairs = explode('@', $text);

		$name = $pairs[0];
		$identifier = $pairs[1];

		$author = Session::instance()->get('author', array());
		if (!isset($author['name']) || $name !== $author['name']) {
			$author = Business::factory('Account')->getAccountByName($name);
			$author = $author->current();
			if (!$author) {
				//获取账户信息失败
				return FALSE;
			}
			
			if ($identifier != md5(Request::$user_agent . Request::$client_ip . $author->getPassword())) {
				//登录失效
				return FALSE;
			}

			$this->_accountId = $author->getAccountId();
			$this->_name = $author->getName();
			$this->_given_name = $author->getGivenName();
			Session::instance()->set('author', get_object_vars($author));
		}
		return TRUE;
	}

	/**
	 * 本地登录
	 * @param $name string
	 * @param $password string
	 * 
	 * @return boolean
	 */
	public function localLogin($name, $password) {
		$author = Business::factory('Account')->getAccountByName($name)->current();
		if (!$author) {
			return false;
		}
		if ($author->getPassword() != md5($password)) {
			return FALSE;
		}

		if ($author->getStatus() == -1) {
			throw new Author_Exception('登录失败，帐号已被屏蔽');
		}

		$this->_accountId = $author->getAccountId();
		$this->_name = $author->getName();
		$this->_given_name = $author->getGivenName();
		Session::instance()->set('author', get_object_vars($author));
		$encrypt = new Encrypt($this->_config['key'], $this->_config['mode'], $this->_config['cipher']);
		$identifier = md5(Request::$user_agent . Request::$client_ip . $author->getPassword());
		$passport = $encrypt->encode($name . '@' . $identifier);

		Cookie::set($this->_config['passport'], $passport);
		return TRUE;
	}

	/**
	 * ldap登录
	 * @param $name string
	 * @param $password string
	 * @throws Author_Exception
	 * @throws Exception
	 * @return boolean
	 */
	public function ldapLogin($name, $password) {
		$ldapConfig = $this->_config['ldap'];
		try {
			$ldap = ldap_connect($ldapConfig['server'], $ldapConfig['port']);
		} catch (Exception $e) {
			return false;
		}

		$ldapname = $name . $ldapConfig['suffix'];
		ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0) or die('Unable to set LDAP opt referrals');
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
		try {
			$ldapBind = ldap_bind($ldap, $ldapname, $password);
		} catch (Exception $e) {
			return false;
		}

		$search = ldap_search($ldap, $ldapConfig['baseDN'], "(userprincipalname=$ldapname)");
		$staff = ldap_get_entries($ldap, $search);
		ldap_unbind($ldap);
		if (!isset($staff[0]['dn'])) {
			return false;
		}
		$dn = $staff[0]['dn'];
		$mail = $staff[0]['mail'][0];
		$cn = stristr(stristr($dn, 'DC', true), 'OU');
		$dnArray = array_filter(explode(',', $cn));
		$givenName = $staff[0]['sn'][0] . $staff[0]['givenname'][0];
		$departmentNames = array();
		
		foreach($dnArray as $key => $value) {
			$replacedName= str_replace('OU=', '', $value);
			array_unshift($departmentNames, $replacedName);
		}

		$parentId = 0;
		$departmentIds = array();
		foreach ($departmentNames as $departmentName) {
			$departmentName = trim($departmentName);
			$existingDepartments = Business::factory('Department')->getDepartmentByName($departmentName);

			$hasDepartment = FALSE;
			foreach ($existingDepartments as $existingDepartment) {
				if ($parentId == $existingDepartment->parent_id) {
					$parentId = $existingDepartment->department_id;
					$departmentIds[] = $existingDepartment->department_id;
					$hasDepartment = TRUE;
					break;
				}
			}

			if (!$hasDepartment) {
				$department = array(
				    'parent_id' => $parentId,
				    'path' => implode(',', $departmentIds),
				    'name' => $departmentName,
				);
				try {
					$result = Business::factory('Department')->create($department);
					if (!$result[0]) {
						throw new Author_Exception('保存部门信息失败');
					}
					$parentId = $result[0];
					$departmentIds[] = $parentId;
				} catch (Exception $e) {
					throw $e;
				}
			}
		}

		$accountInput = array(
		    'name' => $name,
		    'email' => $mail,
		    'given_name' => $givenName,
		);
		
		$result = Business::factory('Account')->getAccountByName($name);
		if (!$result->count()) {
			$accountInput['department_ids'] = $departmentIds;
			$accountInput['role_ids'] = array(Account::ROLE_NORMAL_USER);
			try {
				$result = Business::factory('Account')->create($accountInput);
			} catch (Exception $e) {
				throw new Author_Exception('添加帐号异常');
			}
		} else {
			$account = get_object_vars($result->current());
			$accountId = $account['account_id'];
			$accountInput['mobile'] = $account['mobile'];
			$accountInput['phone'] = $account['phone'];

			if ($account['status'] == -1) {
				throw new Author_Exception('登录失败，帐号已被屏蔽');
			}
			//比对帐号信息
			unset($account['account_id']);
			unset($account['password']);
			unset($account['mobile']);
			unset($account['status']);
			unset($account['create_time']);
			unset($account['update_time']);
			
			$differences = array_diff($account, $accountInput);
			if ($differences) {
				$accountInput['account_id'] = $accountId;
				try {
					$result = Business::factory('Account')->update($accountInput);
				} catch (Exception $e) {
					throw new Author_Exception('更新帐号异常');
				}
			}

			//比对部门信息
			$oldDepartments = Business::factory('Account')->getAccountDepartments($accountId);
			$oldDepartmentIds = array();
			foreach ($oldDepartments as $oldDepartment) {
				$oldDepartmentIds[] = $oldDepartment->department_id;
			}
			if (array_diff($departmentIds, $oldDepartmentIds) || array_diff($oldDepartmentIds, $departmentIds)) {
				try {
					$result = Business::factory('Account')->updateDepartments($accountId, $departmentIds);
				} catch (Exception $e) {
					throw new Author_Exception('更新帐号部门异常');
				}
			}

			//判断角色是否存在
			$roles = Business::factory('Account')->getAccountRoles($accountId);

			if (empty($roles)) {
				$roleIds = array(Account::ROLE_NORMAL_USER);
				try {
					$result = Business::factory('Account')->updateRoles($accountId, $roleIds);
				} catch (Exception $e) {
					throw new Author_Exception('更新帐号角色异常');
				}
			}
		}

		$author = Business::factory('Account')->getAccountByName($name);

		if (!$author) {
			throw new Author_Exception('登录异常');
		}
		$author = $author->current();

		$this->_accountId = $author->getAccountId();
		$this->_name = $author->getName();
		$this->_given_name = $author->getGivenName();
		Session::instance()->set('author', get_object_vars($author));

		$encrypt = new Encrypt($this->_config['key'], $this->_config['mode'], $this->_config['cipher']);
		$identifier = md5(Request::$user_agent . Request::$client_ip . $author->getPassword());
		$passport = $encrypt->encode($name . '@' . $identifier);

		Cookie::set($this->_config['passport'], $passport);

		return TRUE;
	}

	/**
	 * 登出
	 * 
	 * @return boolean
	 */
	public function logout() {
		Cookie::delete($this->_config['passport']);
		Session::instance()->delete('author');
		return Cookie::get($this->_config['passport']) ? FALSE : TRUE;
	}

	/**
	 * 
	 */

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
		if ($this->_departments === NULL) {
			$this->_departments = Business::factory('Account')->getAccountDepartments($this->_accountId);
		}
		return $this->_departments;
	}

	/**
	 * 返回账户角色
	 * @return object
	 */
	public function getRoles() {
		if ($this->_roles === NULL) {
			$this->_roles = Business::factory('Account')->getAccountRoles($this->_accountId);
		}
		return $this->_roles;
	}

	/**
	 * 返回账户权限
	 * @return object
	 */
	public function getPrivileges() {
		if ($this->_privileges === NULL) {
			$this->_privileges = Business::factory('Account')->getPrivilegesByAccountId($this->_accountId);
		}
		return $this->_privileges;
	}
	/**
	 * 获取帐号角色
	 * @return string
	 */
	public static function getRolesName() {
		$roles = Author::roles();
		$roleString = '';
		foreach ($roles as $role) {
			$roleString .= $role->name.',';
		}
		return rtrim($roleString,',');
	}

}
