<?php

/**
 * 
 * @package default
 * @author  zhangshijie
 */
class Business_Gvc_Channel extends Business {

	/**
	 * @param $overtime 超时时间 单位 s
	 * @param $worker_type
	 * @return mixed
	 */
	public function getChannelTask($worker_type,$overtime) {
		return Dao::factory('Gvc_Channel')->getChannelTask($worker_type,$overtime);
	}

	public function updateChannelTask($channel_task_id) {
		return Dao::factory('Gvc_Channel')->updateChannelTask($channel_task_id);
	}

	public function getChannel($channel_id) {
		return Dao::factory('Gvc_Channel')->getChannel($channel_id);
	}

	public function updateChannel($worker_ip, $channel_task_id) {
		return Dao::factory('Gvc_Channel')->updateChannel($worker_ip, $channel_task_id);
	}

	

}