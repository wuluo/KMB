<?php

/**
 * 频道业务逻辑
 * @author: panchao
 * Time: 11:23
 */
class Business_Channel extends Business {

	/**
	 * 根据 channel_id 来获取 channel
	 * @param $channelId
	 * @return
	 */
	public function getChannelByChannelId($channelId) {
		return Dao::factory('Channel')->getChannelByChannelId($channelId);
	}

	/**
	 * 根据 push_stream_id 来获取频道信息
	 * @param $pushStreamId
	 */
	public function getChannelByPushStreamId($pushStreamId) {
		return Dao::factory('Channel')->getChannelByPushStreamId($pushStreamId);
	}
	/**
	 * 获取所有频道信息
	 * @return object
	 */
	public function getAlreadyAssignedMachine() {
		$channels = Dao::factory('Channel')->getChannels();
		return $channels;

	}
}