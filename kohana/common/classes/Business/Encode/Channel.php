<?php

/**
 * 
 * @package default
 * @author  zhangshijie
 */
class Business_Encode_Channel extends Business {

	/**
	 * @param $overtime 超时时间 单位 s
	 * @param $worker_type
	 * @return mixed
	 */
	public function getChannelTask($worker_type,$worker_ip,$overtime) {
		return Dao::factory('Encode_Channel')->getChannelTask($worker_type,$worker_ip,$overtime);
	}

	public function updateChannelTask($channel_task_id) {
		$channel_task_id = explode(',', trim($channel_task_id));
		return Dao::factory('Encode_Channel')->updateChannelTask($channel_task_id);
	}

	public function getChannelAll($channel_id) {
		return Dao::factory('Encode_Channel')->getChannelAll($channel_id);
	}


	public function updateChannel($worker_ip, $channel_task_id) {
		return Dao::factory('Encode_Channel')->updateChannel($worker_ip, $channel_task_id);
	}


}