<?php

/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/10
 * @time:   14:02
 */
class Dao_Privileges extends Dao {

	protected $_db = 'api';
	protected $_tableName = 'privilege';
	protected $_modelName = 'Model_Privileges';

	public function getList() {
		$select = DB::select("*")
			->from($this->_tableName);
		return $select->execute($this->_db)->as_array();
	}

	public function getPrivilegeByAppId($appId) {
		$select = DB::select("app_privilege.app_id as apid", "app_privilege.type", "privilege.*")
			->from("app_privilege")
			->join('privilege')->on('app_privilege.privilege_id', '=', 'privilege.privilege_id')
			->where("app_privilege.app_id", "=", $appId);
		return $select->execute($this->_db)->as_array();
	}
}