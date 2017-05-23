<?php
/**
 * 账号信息数据访问层
 */
class Dao_Menu extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'menus';
	
	//获取role_id
	public function getAllMenus() {
		return DB::select('*')
				->from($this->_tableName)
				->where('status', '=', 1)
				->where('type', '=', 0)
				->order('weight','desc')
				->as_object()
				->execute($this->_db);
	}
}
