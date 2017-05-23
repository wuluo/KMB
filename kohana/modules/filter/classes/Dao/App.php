<?php
/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/7
 * @time:   11:20
 */
class Dao_App extends Dao{
	protected $_db = 'api';
	protected $_tableName = 'app';
	protected $_modelName = 'Model_App';
	
	public function getList(){
		$select = DB::select("*")
			->from($this->_tableName);
		return $select->execute($this->_db)->as_array();
	}
}