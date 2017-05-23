<?php

class Dao_Account extends Dao {

	protected $_primaryKey = 'account_id';
	protected $_tableName = 'account';
	protected $_db = 'default';
	protected $_modelName = 'Model_Account';

	const STATUS_DELETE = 1;
	const STATUS_NORMAL = 0;


	/**
	 * 根据账号ID获取账号信息
	 * @param integer $accountId
	 * @return array
	 */
	public function getAccountByAccountId($accountId) {
		return DB::select('*')
			->from($this->_tableName)
			->where($this->_primaryKey, '=', $accountId)
			->as_object('Model_Account')
			->execute($this->_db);
	}

	/**
	 * 根据多个账号ID获取账号信息
	 * @param array $accountIds
	 * @return array
	 */
	public function getAccountsByAccountIds($accountIds) {
		return DB::select('*')
			->from($this->_tableName)
			->where($this->_primaryKey, 'in', $accountIds)
			->as_object('Model_Account')
			->execute($this->_db);
	}

	/**
	 * 根据名字获取账号信息
	 * @param string $name
	 * @return array
	 */
	public function getAccountByName($name) {
		return DB::select('*')
			->from($this->_tableName)
			->where('name', '=', $name)
			->as_object('Model_Account')
			->execute($this->_db);
	}

	/**
	 * 插入一行
	 * @param array $values
	 * @return array
	 */
	public function insert(array $values) {
		return DB::insert($this->_tableName)
			->columns(array_keys($values))
			->values(array_values($values))
			->execute($this->_db);
	}

	/**
	 * 更新一行
	 * @param array $values
	 * @return integer
	 */
	public function updateByAccountId($accountId, $values) {
		if (!$values) {
			return false;
		}
		return DB::update($this->_tableName)
			->set($values)
			->where('account_id', '=', $accountId)
			->execute($this->_db);
	}

	/**
	 * 删除一行（标记删除）
	 * @param number $accountId
	 * @return integer
	 */
	public function delete($accountId) {
		return DB::update($this->_tableName)
			->set(array('is_delete' => self::STATUS_DELETE))
			->where('account_id', '=', $accountId)
			->execute($this->_db);
	}

	/**
	 * 根据名字或昵称查找账号信息
	 * @param string $keyword
	 * @return object
	 */
	public function getAccountByNameOrGivenName($keyword) {
		return DB::select('*')
			->from($this->_tableName)
			->where('name', '=', $keyword)
			->or_where('given_name', '=', $keyword)
			->as_object('Model_Account')
			->execute($this->_db);
	}

	/**
	 * 统计
	 * @param string $keyword
	 * @param array $statuses
	 */
	public function count($keyword = NULL, array $statuses = array(self::STATUS_NORMAL, self::STATUS_DELETE)) {
		$select = DB::select(DB::expr('COUNT(*) AS recordCount'))
			->from($this->_tableName);
		if ($keyword !== NULL) {
			$select->or_where_open();
			$select->where('account_id', '=', $keyword);
			$select->or_where('name', 'LIKE', '%' . $keyword . '%');
			$select->or_where('given_name', 'LIKE', '%' . $keyword . '%');
			$select->or_where_close();
			$select->where('is_delete', 'IN', $statuses);
		}
		return $select->execute($this->_db)->get('recordCount');
	}

	/**
	 * 按关键字查询
	 * @param string $keyword
	 * @param integer $number
	 * @param integer $offset
	 * @param array $statuses
	 */
	public function getAccountsByKeyword($keyword = NULL, $number = 0, $offset = 0, array $statuses = array(self::STATUS_NORMAL, self::STATUS_DELETE)) {
		$select = DB::select('*')
			->from($this->_tableName)
			->order_by('is_delete', 'asc')
			->order_by('account_id', 'DESC');
		if ($keyword !== NULL) {
			$select->or_where_open();
			$select->where('account_id', '=', $keyword);
			$select->or_where('name', 'LIKE', '%' . $keyword . '%');
			$select->or_where('given_name', 'LIKE', '%' . $keyword . '%');
			$select->or_where_close();
			$select->where('is_delete', 'IN', $statuses);
		}
		if ($offset) {
			$select->offset($offset);
		}
		if ($number) {
			$select->limit($number);
		}
		return $select->as_object('Model_Account')->execute($this->_db);
	}

}
