<?php
class Dao_Channel_Expire extends Dao {
		
	protected $_db = 'system';
	protected $_tableName = 'channel_expire';
	protected $_primaryKey = 'channel_expire_id';
	protected $_modelName = 'Model_Channel_Expire';

	public function insertChannelExpire($flow) {
		$columns = array_keys($flow[0]);
		$insert = DB::insert($this->_tableName)
			->columns($columns);
			foreach($flow as $value) {
				$insert->values(array_values($value));
			}
		return $insert->execute($this->_db);
	}
}