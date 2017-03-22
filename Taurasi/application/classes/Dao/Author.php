<?php
/**
 * 账号信息数据访问层
 */
class Dao_Author extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'admin';
	protected $_primaryKey = 'id';
	
	//获取用户名参数的所有信息
	public function getUser($username) {
		return DB::select('*')
				->from($this->_tableName)
				->where('user_name', '=', $username)
				->as_object('Model_User')
				->execute($this->_db);
	}

	/**
	 * @param $array
	 * @param $id
	 * @return object
	 * @desc 更新用户信息
	 */
	public function setUser($array, $id){
		return DB::update($this->_tableName)
			->set($array)
			->where('id', '=', $id)
			->as_object('Model_User')
			->execute($this->_db);
	}
}
