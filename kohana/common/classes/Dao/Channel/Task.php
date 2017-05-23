<?php
class Dao_Channel_Task extends Dao {
		
	protected $_db = 'system';
	protected $_tableName = 'channel_task';
	protected $_primaryKey = 'channel_task_id';
	protected $_modelName = 'Model_Channel_Task';

	public function insertChannelTask($task) {
		$columns = array_keys($task[0]);
		$insert = DB::insert($this->_tableName)
			->columns($columns);
			foreach($task as $value) {
				$insert->values(array_values($value));
			}
		return $insert->execute($this->_db);
	}
}