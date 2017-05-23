<?php
/**
 * 
 * @author: zhangshijie
 */
class Dao_Encode_Channel extends Dao {
	//config.php里的数据键
	protected $_db = 'system';

	protected $_tableName = 'channel_task';

	protected $_tableName2 = 'channel';


	/**
	 * overtime 单位s
	 * @return 
	 */
	public function getChannelTask($worker_type,$worker_ip,$overtime) {
		$t = time()-$overtime;

		return DB::select('*')
			->from($this->_tableName)
			->where('worker_time','=',0)
			->and_where('worker_type','=',$worker_type)
			->and_where('encode_ip','=',$worker_ip)
			->or_where_open()
			->where('worker_time','!=',0)
			->and_where('worker_type','=',$worker_type)
			->and_where('encode_ip','=',$worker_ip)
			->and_where('respond_time','=',0)
			->and_where("worker_time",'>',"$t")
			->or_where_close()
			->execute($this->_db);
	}

	public function updateChannelTask($channel_task_id) {
		return DB::update($this->_tableName)
			->set(array('respond_time'=> time() ) )
			->where('channel_task_id', 'in', $channel_task_id)
			->execute($this->_db);
	}

	public function getChannelAll($channel_id) {
		return DB::select('*')
			->from($this->_tableName2)
			->where('channel_id','in',$channel_id)
			->execute($this->_db);
	}


	public function updateChannel($worker_ip, $channel_task_id) {
		return DB::update($this->_tableName)
			->set(array('worker_time'=> time()) )
			->where('channel_task_id', 'in', $channel_task_id)
			->execute($this->_db);
	}

}