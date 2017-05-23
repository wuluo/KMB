<?php
/**
 * 账号信息数据访问层
 */
class Dao_Menu extends Dao {
	protected $_db = 'default';
	protected $_tableName = 'menus';
	
	//获取指定类型所有菜单
	public function getAllMenus($type = 0) {
		return DB::select('*')
				->from($this->_tableName)
				->where('status', '=', 1)
				->where('type', '=', $type)
				->order_by('weight','desc')
				->execute($this->_db)
				->as_array();
	}
}
