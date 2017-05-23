<?php

class Business_Account extends Business {

	/**
	 * 获得帐号列表分页
	 * @param string $keyword
	 * @param number $page
	 * @param number $size
	 * @param string $key
	 */
	public function getPagination($keyword = '', $page = 1, $size = 1, $key = 'page') {
		if ($keyword) {
			$count = Dao::factory('Account')->count($keyword);
		} else {
			$count = Dao::factory('Account')->count();
		}

		return Pagination::factory()
			->total($count)
			->number($size)
			->key($key)
			->execute();
	}

	/**
	 * 获得帐号列表
	 * @param string $keyword
	 * @param number $number
	 * @param number $offset
	 */
	public function getAccountByKeyword($keyword = '', $number = 0, $offset = 0) {
		if ($keyword) {
			$accounts = Business::factory('Account')->getAccountsByKeyword($keyword, $number, $offset);
		} else {
			$accounts = Business::factory('Account')->getAccounts($number, $offset);
		}

		$accountIds = array();
		foreach ($accounts as $account) {
			$accountIds[] = $account->getAccountId();
		}

		//获得部门
		$departmentIds = array();
		$accountDepartments = Dao::factory('Account_Department')->getAccountDepartmentsByAccountIds($accountIds);
		foreach ($accountDepartments as $accountDepartment) {
			$departmentIds[] = $accountDepartment->getDepartmentId();
		}
		$departments = Dao::factory('Department')->getDepartmentsByDepartmentIds($departmentIds);

		foreach ($accounts as $account) {
			$account->departments = array();

			foreach ($accountDepartments as $accountDepartment) {
				if ($accountDepartment->getAccountId() != $account->getAccountId()) {
					continue;
				}
				foreach ($departments as $department) {
					if ($accountDepartment->getDepartmentId() != $department->getDepartmentId()) {
						continue;
					}

					array_push($account->departments, $department);
				}
			}
		}

		return $accounts;
	}

	/**
	 * 插入新账号
	 * @param array $values
	 * @return array
	 */
	public function create(array $values) {
		$fields = array(
			'employee_id' => 0,
			'name' => '',
			'given_name' => '',
			'email' => '',
			'password' => '',
			'mobile' => '',
			'phone' => '',
			'create_time' => time(),
			'update_time' => time(),
		);

		$departmentIds = $values['department_ids'];
		//获取传入的用户信息
		$name = Arr::get($values, 'name', '');
		$password = Arr::get($values, 'password', '');
		$email = Arr::get($values, 'email', '');
		$mobile = Arr::get($values, 'mobile', '');
		$phone = Arr::get($values, 'phone', '');
		//判断必填项
		$errors = array();
		if (!$name) {
			throw new Business_Exception("账号不能为空");
		}
		if (preg_match("/@/", $name)) {
			throw new Business_Exception("账号不能包含字符@");
		}
		if (!$departmentIds) {
			throw new Business_Exception("请选择部门");
		}
		if (!$email) {
			throw new Business_Exception("邮箱不能为空");
		}

		$account = Dao::factory('Account')->getAccountByName($name);
		if (count($account) > 0) {
			throw new Business_Exception('账号已存在');
		}
		if (isset($values['password'])) {
			$values['password'] = md5($values['password']);
		}
		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$result = Dao::factory('Account')->insert($values);
		if (!$result[0]) {
			throw new Business_Exception('新增账号失败！');
		}
		$accountId = $result[0];

		$result = Dao::factory('Account_Department')->generate($accountId, $departmentIds, time());
		if (!isset($result[0]) || !$result[0]) {
			$errors[] = '部门信息保存失败！';
		}
		if ($errors) {
			throw new Business_Exception('新增账号成功。' . implode(' ', $errors));
		}
		return true;
	}

	/**
	 * 修改账号
	 * @param array $values
	 * @return integer
	 */
	public function update(array $values) {
		$fields = array(
			'employee_id' => 0,
			'name' => '',
			'given_name' => '',
			'email' => '',
			'password' => '',
			'mobile' => '',
			'phone' => '',
			'update_time' => time(),
		);
		$departmentIds = Arr::get($values, 'department_ids', '');
		$accountId = Arr::get($values, 'account_id', '');
		$name = Arr::get($values, 'name', '');
		$password = Arr::get($values, 'password', '');
		$email = Arr::get($values, 'email', '');

		$errors = array();
		if (!$name) {
			$errors[] = '用户名不能为空！';
		}
		if (preg_match("/@/", $name)) {
			$errors[] = '用户名不能包含字符@';
		}
		if (!$email) {
			$errors[] = '邮箱不能为空！';
		}
		if ($errors) {
			throw new Business_Exception(implode(' ', $errors));
		}

		$account = Dao::factory('Account')->getAccountByName($name)->current();

		if (count($account) > 0 && $account->account_id != $accountId) {
			throw new Business_Exception('账号重复！');
		}
		unset($values['account_id']);
		if (!$password) {
			unset($fields['password']);
		} else {
			$values['password'] = md5($values['password']);
		}

		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$result = Dao::factory('Account')->updateByAccountId($accountId, $values);
		if ($result === false) {
			throw new Business_Exception('修改账号失败！');
		}
		return true;
	}

	/**
	 * 修改账号基本信息
	 * @param array $values
	 * @return integer
	 */
	public function updateInfo(array $values) {
		$fields = array(
			'given_name' => '',
			'email' => '',
			'mobile' => '',
			'phone' => '',
			'update_time' => time(),
		);

		$accountId = Arr::get($values, 'account_id', '');
		$email = Arr::get($values, 'email', '');
		$mobile = Arr::get($values, 'mobile', 0);
		$phone = Arr::get($values, 'phone', 0);

		$errors = array();
		if (!$email) {
			$errors[] = '邮箱不能为空！';
		}
		if ($errors) {
			throw new Business_Exception(implode(' ', $errors));
		}

		unset($values['account_id']);

		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$result = Dao::factory('Account')->updateByAccountId($accountId, $values);
		if (!$result) {
			throw new Business_Exception('修改账号失败！');
		}

		return 1;
	}

	/**
	 * 修改账号密码
	 * @param array $values
	 * @return integer
	 */
	public function updatePassword(array $values) {
		$fields = array(
			'password' => '',
			'update_time' => time(),
		);

		$accountId = Arr::get($values, 'account_id', '');
		$oldPassword = Arr::get($values, 'oldpassword', '');
		$password = Arr::get($values, 'password', '');
		$rePassword = Arr::get($values, 'repassword', '');

		$errors = array();
		if (!Valid::not_empty($password)) {
			$errors[] = '新密码不能为空！';
		}
		if (!Valid::equals($password, $rePassword)) {
			$errors[] = '两次输入的密码不匹配！';
		}
		if (!Valid::min_length($password, 6)) {
			$errors[] = '密码长度不足6位！';
		}

		$account = Dao::factory('Account')->getAccountByAccountId($accountId);
		if (isset($account[0]['password']) && $account[0]['password'] != '') {
			if (md5($oldPassword) != $account[0]['password']) {
				throw new Business_Exception('当前密码错误！');
			}
		}
		if ($errors) {
			throw new Business_Exception(implode(' ', $errors));
		}

		unset($values['account_id']);
		$values['password'] = md5($values['password']);

		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$result = Dao::factory('Account')->updateByAccountId($accountId, $values);
		if (!$result) {
			throw new Business_Exception('修改密码失败！');
		}

		return 1;
	}

	/**
	 * 更新帐号部门
	 * @param integer $accountId
	 * @param array $departmentIds
	 * @return integer
	 */
	public function updateDepartments($accountId, $departmentIds) {
		//ignore errors
		Dao::factory('Account_Department')->deleteByAccountId($accountId);

		if (!$departmentIds) {
			throw new Business_Exception('请选择部门。');
		}
		return Dao::factory('Account_Department')->generate($accountId, $departmentIds, time());
	}

	/**
	 * 根据名字查找账号信息
	 * @param string $name
	 * @return array
	 */
	public function getAccountByName($name) {
		return Dao::factory('Account')->getAccountByName($name);
	}

	/**
	 * 根据账号id查询账号信息
	 * @param integer $accountId
	 * @return array
	 */
	public function getAccountByAccountId($accountId) {
		return Dao::factory('Account')->getAccountByAccountId($accountId);
	}

	/**
	 * 根据关键字统计记录数
	 * @param string $keyword
	 * @return integer
	 */
	public function countByKeyword($keyword = NULL) {
		return Dao::factory('Account')->count($keyword, array(Dao_Account::STATUS_DELETE, Dao_Account::STATUS_NORMAL));
	}

	/**
	 * 根据关键字查询
	 * @param string $keyword
	 * @param number $number
	 * @param number $offset
	 * @return array
	 */
	public function getAccountsByKeyword($keyword = NULL, $number = 0, $offset = 0) {
		return Dao::factory('Account')->getAccountsByKeyword($keyword, $number, $offset, array(Dao_Account::STATUS_DELETE, Dao_Account::STATUS_NORMAL));
	}

	/**
	 * 统计所有记录数
	 * @return integer
	 */
	public function count() {
		return Dao::factory('Account')->count(NULL, array(Dao_Account::STATUS_DELETE, Dao_Account::STATUS_NORMAL));
	}

	/**
	 * 查询列表
	 * @param number $number
	 * @param number $offset
	 * @return array
	 */
	public function getAccounts($number = 0, $offset = 0) {
		return Dao::factory('Account')->getAccountsByKeyword(NULL, $number, $offset, array(Dao_Account::STATUS_DELETE, Dao_Account::STATUS_NORMAL));
	}

	/**
	 * 删除账号（标记删除）
	 * @param integer $accountId
	 * @return integer
	 */
	public function delete($accountId = 0) {
		if ($accountId == 1) {
			throw new Business_Exception('ROOT不能删除！');
		}
		return Dao::factory('Account')->delete($accountId);
	}

	/**
	 * 恢复账号
	 * @param integer $accountId
	 * @return integer
	 */
	public function renormal($accountId = 0) {
		$values = array('is_delete' => 0);
		return Dao::factory('Account')->updateByAccountId($accountId, $values);
	}

	/**
	 * 获取账号部门
	 * @param integer $accountId
	 * @return array
	 */
	public function getAccountDepartments($accountId = 0) {
		$departmentIds = array();
		$accountDepartments = Dao::factory('Account_Department')->getAccountDepartmentsByAccountId($accountId);
		foreach ($accountDepartments as $accountDepartment) {
			$departmentIds[] = $accountDepartment->department_id;
		}
		if (!$departmentIds) {
			return array();
		}
		return Dao::factory('Department')->getDepartmentsByDepartmentIds($departmentIds);
	}

	/**
	 * 获取一组账号的部门关系
	 * @param array $accountIds
	 * @return array
	 */
	public function getAccountDepartmentsByAccountIds(array $accountIds = array()) {
		return Dao::factory('Account_Department')->getAccountDepartmentsByAccountIds($accountIds);
	}

	/**
	 * 根据名字或昵称查找账号信息
	 * @param string $keyword
	 * @return array
	 */
	public function getAccountByNameOrGivenName($keyword = '') {
		if (!$keyword) {
			return NULL;
		}
		return Dao::factory('Account')->getAccountByNameOrGivenName($keyword);
	}

}
