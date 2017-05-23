<?php
class Dao_Test extends Dao {
		
		protected $_db = 'account';
		protected $_tableName = 'account';
		protected $_primaryKey = 'account_id';
		protected $_modelName = 'Model_Test';
		
		const STATUS_DELETE = -1;
		const STATUS_NORMAL = 0;
		
		
		public function getList() {
			return DB::select('*')->from($this->_tableName)->limit(10)->as_object('Model_Test')->execute($this->_db);
		}
		
	
}