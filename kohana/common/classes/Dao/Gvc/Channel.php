<?php
/**
 * 
 * @author: zhangshijie
 */
class Dao_Gvc_Channel extends Dao {
	//config.php里的数据键
	protected $_db = 'system';

	protected $_tableName = 'channel_task';

	protected $_tableName2 = 'channel';


	/**
	 * overtime 单位s
	 * @return 
	 */
	public function getChannelTask($worker_type,$overtime) {
		$t = time()-$overtime;
		return DB::select('*')
			->from($this->_tableName)
			->where('worker_time','=',0)
			->and_where('worker_type','=',$worker_type)
			->or_where_open()->
			where('worker_time','!=',0)->
			and_where('respond_time','=',0)->
			and_where("worker_time",'>',"$t")
			->and_where('worker_type','=',$worker_type)
			->or_where_close()
			->limit(1)
			->execute($this->_db);
	}

	public function updateChannelTask($channel_task_id) {
		return DB::update($this->_tableName)
			->set(array('respond_time'=> time() ) )
			->where('channel_task_id', '=', $channel_task_id)
			->execute($this->_db);
	}

	public function getChannel($channel_id) {
		return DB::select('*')
			->from($this->_tableName2)
			->where('channel_id','=',$channel_id)
			->limit(1)
			->execute($this->_db);
	}

	public function updateChannel($worker_ip, $channel_task_id) {
		return DB::update($this->_tableName)
			->set(array('worker_time'=> time(), 'worker_ip' => $worker_ip) )
			->where('channel_task_id', '=', $channel_task_id)
			->execute($this->_db);
	}

}