<?php

/**
 * 申请流，验证流操作逻辑
 * @author  quanhengzhe
 * @date:   2017/5/12
 * @time:   11:17
 */
class Business_Apply extends Business{

	private $_business = 'Apply';
	/**
	 * 大型直播申请流接口
	 * @param  array $flowsInformation 申请条件
	 * @return object
	 */
	public function getApplicationFlow($flowsInformation) {
		
		//验证申请流时post参数
		$flowsInformation = $this->_verifyFlowsData($flowsInformation);

		if (!isset($flowsInformation['end_time']) || empty($flowsInformation['end_time'])) {
			$error[] = '直播结束时间不能为空 ';
		}
		//合计清晰度有几个
		$countClarity = count($flowsInformation['clarity']);
		if ($flowsInformation['is_convert'] && $countClarity < 1) {
			$error[] = '转出多路流必须选择转码 ';
		}
		if ($flowsInformation['warm_model'] == 2) {
			//拉，就要给我地址，push自己的model_address
			if (isset($flowsInformation['warm_input']) || empty($flowsInformation['warm_input'])) {
				$error[] = '拉模式缓场地址必须添加 ';
			}
		}
		if ($flowsInformation['model'] == 2) {
			if (empty($flowsInformation['model_address'])) {
				$error[] = '拉模式推流地址必须添加 ';
			}
		}
		if (!empty($error)) {
			throw new Business_Exception(implode(' ', $error));
		}
		/**验证END**/

		$microtime = $this->microtime_float();
		$pushStreamId = md5($flowsInformation['start_time'].$flowsInformation['end_time'].$microtime.'push'.uniqid());
		$playStreamId = md5($flowsInformation['start_time'].$flowsInformation['end_time'].$microtime.'play'.uniqid());

		//申请输入流地址
		if ($flowsInformation['model'] == 1) {
			$streamPushMachine = Business::factory('Machine_Machine')->getApplicationMachine('stream', 'stream', 1);
			$modelAddress = $flowsInformation['protocol'].$streamPushMachine['0']['network_ip'].':1935/live/'.$pushStreamId;
		} else {
			$modelAddress = $flowsInformation['model_address'];
		}
		//暖场地址
		if (!empty($flowsInformation['warm_start_time'])) {
			if ($flowsInformation['warm_model'] == 2) {
				$warmInput = $flowsInformation['warm_input'];
			} else {
				$warmInput = $modelAddress;
			}
		} else {
			$warmInput = '';
		}
		
		//申请编码器地址
		if ($flowsInformation['is_convert']) {
			$convertMachine = Business::factory('Machine_Machine')->getApplicationMachine('convert', 'live');
			$convertIp = $convertMachine['0']['network_ip'];
		} else {
			$convertIp = '';
		}
	
		//申请输出流地址
		$streamPlayMachine = Business::factory('Machine_Machine')->getApplicationMachine('stream', 'stream', $countClarity);
		//转播地址
		if (isset($flowsInformation['forward']) || !empty($flowsInformation['forward'])) {
			$forward = json_encode($flowsInformation['forward']);
		} else {
			$forward = '';
		}
		//暖场时间
		if (isset($flowsInformation['warm_start_time']) || !empty($flowsInformation['warm_start_time'])) {
			$warm_start_time = $flowsInformation['warm_start_time'];
		} else {
			$warm_start_time = '';
		}
		$nowTime = time();
		//返回流地址
		$flowAddress = array();
		//生成插入channel表数据
		if ($countClarity > 1) {
			
			//多路清晰度多条记录(输出流)
			foreach ($streamPlayMachine as $key => $ips) {
				
				$flows[$key]['model'] = $flowsInformation['model'];
				$flows[$key]['warm_model'] = $flowsInformation['warm_model'];
				$flows[$key]['model_address'] = $modelAddress;
				$flows[$key]['encode_ip'] = $convertIp;
				$flows[$key]['is_convert'] = $flowsInformation['is_convert'];
				$flows[$key]['convert_output'] = $flowsInformation['protocol'].$ips['network_ip'].':1935/'.$playStreamId;
				$flows[$key]['forward'] = $forward;
				$flows[$key]['warm_input'] = $warmInput;
				$flows[$key]['protocol'] = $flowsInformation['protocol'];
				$flows[$key]['push_stream_id'] = $pushStreamId;
				$flows[$key]['play_stream_id'] = $playStreamId;
				$flows[$key]['clarity'] = $flowsInformation['clarity'][$key];
				$flows[$key]['is_review'] = $flowsInformation['is_review'];
				$flows[$key]['is_record'] = $flowsInformation['is_record'];
				$flows[$key]['is_cdn'] = $flowsInformation['is_cdn'];
				$flows[$key]['warm_start_time'] = $warm_start_time;
				$flows[$key]['start_time'] = $flowsInformation['start_time'];
				$flows[$key]['end_time'] = $flowsInformation['end_time'];
				$flows[$key]['create_time'] = $nowTime;
				//组合流地址
				$flowAddress['model_address'] = $flowsInformation['protocol'].$streamPushMachine['0']['outside_ip'].':1935/live/'.$pushStreamId;
				if ($flowsInformation['is_convert'] || isset($convertMachine['0']['network_ip'])) {
					$flowAddress['convert'] = $convertMachine['0']['network_ip'];
				}
				$flowAddress['output'][] = $flowsInformation['protocol'].$ips['outside_ip'].':1935/live/'.$playStreamId;
			}
		} else {//一种清晰度
			$flows['0']['model'] = $flowsInformation['model'];
			$flows['0']['warm_model'] = $flowsInformation['warm_model'];
			$flows['0']['model_address'] = $modelAddress;
			$flows['0']['encode_ip'] = $convertIp;
			$flows['0']['is_convert'] = $flowsInformation['is_convert'];
			$flows['0']['convert_output'] = $flowsInformation['protocol'].$streamPlayMachine['0']['network_ip'].':1935/'.$playStreamId;
			$flows['0']['forward'] = $forward;
			$flows['0']['warm_input'] = $warmInput;
			$flows['0']['protocol'] = $flowsInformation['protocol'];
			$flows['0']['push_stream_id'] = $pushStreamId;
			$flows['0']['play_stream_id'] = $playStreamId;
			$flows['0']['clarity'] = $flowsInformation['clarity']['0'];
			$flows['0']['is_review'] = $flowsInformation['is_review'];
			$flows['0']['is_record'] = $flowsInformation['is_record'];
			$flows['0']['is_cdn'] = $flowsInformation['is_cdn'];
			$flows['0']['warm_start_time'] = $warm_start_time;
			$flows['0']['start_time'] = $flowsInformation['start_time'];
			$flows['0']['end_time'] = $flowsInformation['end_time'];
			$flows['0']['create_time'] = $nowTime;
			//组合流地址
			$flowAddress['input'] = $flowsInformation['protocol'].$streamPushMachine['0']['outside_ip'].':1935/live/'.$pushStreamId;
			if ($flowsInformation['is_convert'] || isset($convertMachine['0']['network_ip'])) {
				$flowAddress['convert'] = $convertMachine['0']['network_ip'];
			}
			$flowAddress['output'][] = $flowsInformation['protocol'].$streamPlayMachine['0']['outside_ip'].':1935/live/'.$playStreamId;
		}
		
		//插入channel
		$channel = Dao::factory('Channel')->insertChannel($flows);
		if (!$channel) {
			throw new Business_Exception('插入channel数据失败');
		}
		//计算插入的channelId
		$result = $channel['1'];
		$channelIds = array();
		for($i = 0; $i < $result; $i++){
			$channelIds[] = $channel['0'] + $i;
		}
		$convertData = [];
		$forwardData = [];
		$recordData = [];
		$reviewData = [];
		//生成插入channel_task表数据
		//编码按清晰度增加任务
		foreach ($channelIds as $channelKey => $channelId) {
			$convertData[$channelKey]['channel_id'] = $channelId;
			$convertData[$channelKey]['model'] = $flowsInformation['model'];
			$convertData[$channelKey]['encode_ip'] = $convertIp;
			$convertData[$channelKey]['worker_type'] = 1;
			$convertData[$channelKey]['warm_start_time'] = $warm_start_time;
			$convertData[$channelKey]['start_time'] = $flowsInformation['start_time'];
			$convertData[$channelKey]['end_time'] = $flowsInformation['end_time'];
			$convertData[$channelKey]['create_time'] = $nowTime;
			
		}
		
		//回看按清晰度增加任务
		if ($flowsInformation['is_review']) {
			foreach ($channelIds as $channelKey => $channelId) {
				$reviewData[$channelKey]['channel_id'] = $channelId;
				$reviewData[$channelKey]['model'] = $flowsInformation['model'];
				$reviewData[$channelKey]['encode_ip'] = $convertIp;
				$reviewData[$channelKey]['worker_type'] = 4;
				$reviewData[$channelKey]['warm_start_time'] = $warm_start_time;
				$reviewData[$channelKey]['start_time'] = $flowsInformation['start_time'];
				$reviewData[$channelKey]['end_time'] = $flowsInformation['end_time'];
				$reviewData[$channelKey]['create_time'] = $nowTime;
				
			}
		}
		//转播按最高清晰度增加一条任务
		if (isset($flowsInformation['forward']) || !empty($flowsInformation['forward'])) {
			$forwardData['0']['channel_id'] = $channelIds['0'];
			$forwardData['0']['model'] = $flowsInformation['model'];
			$forwardData['0']['encode_ip'] = $convertIp;
			$forwardData['0']['worker_type'] = 2;
			$forwardData['0']['warm_start_time'] = $warm_start_time;
			$forwardData['0']['start_time'] = $flowsInformation['start_time'];
			$forwardData['0']['end_time'] = $flowsInformation['end_time'];
			$forwardData['0']['create_time'] = $nowTime;
		}
		//录制最高清晰度一条任务
		if ($flowsInformation['is_record']) {
			//查找最高清晰度channel
			$MustClarityChannel = Dao::factory('Channel')->getMustClarityChannels($channelIds)->current();
			$recordData['0']['channel_id'] = $MustClarityChannel->channel_id;
			$recordData['0']['model'] = $flowsInformation['model'];
			$recordData['0']['encode_ip'] = $convertIp;
			$recordData['0']['worker_type'] = 3;
			$recordData['0']['warm_start_time'] = $warm_start_time;
			$recordData['0']['start_time'] = $flowsInformation['start_time'];
			$recordData['0']['end_time'] = $flowsInformation['end_time'];
			$recordData['0']['create_time'] = $nowTime;
		}
		$taskData = array_merge($convertData,$reviewData,$forwardData,$recordData);
		
		$channelTask = Dao::factory('Channel_Task')->insertChannelTask($taskData);
		if (!$channelTask) {
			$selectChannel = Dao::factory('Channel')->getChannels($channelIds);
			foreach ($flows as $fk => &$channelFlow) {
				$channelFlow['is_delete'] = 1;
				$channelFlow['update_time'] = time();
			}
			//插入channel
			$channelExpire = Dao::factory('Channel_Expire')->insertChannelExpire($flows);
			//删除无用channel
			$channelHistory = Dao::factory('Channel')->deleteChannel($channelIds);
			if (!$channelExpire && !$channelHistory) {
				throw new Business_Exception('转移历史channel数据失败');
			}
			throw new Business_Exception('插入channelTask表数据失败');
		}
		return $flowAddress;
	}

	public function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());  
		return ((float)$usec + (float)$sec);  
	}
	/**
	 * 推流验证token
	 * @param  string $name token
	 * @return 状态 500 失败 200 成功
	 */
	public function onPublishVerifyInput($name) {
		if (strlen($name) != 32) {
			Header("HTTP/1.1 500");
			exit;
		}
		$channels = Dao::factory('Channel')->getChannelByPushStreamId($name);
		if (!$channels->count()) {
			Header("HTTP/1.1 500");
			exit;
		}
		foreach ($channels as $channel) {
			$channelIds[] = $channel->channel_id;
		}
		$channelObj = $channels->current();
		$intervalsTime = Kohana::$config->load('live.streamIntervals.time');
		if (!empty($channelObj->stream_end_time)) {
			$time = time() - $channelObj->stream_end_time;
			//判断流是不是活动的
			if ($time > $intervalsTime) {
				Header("HTTP/1.1 500");
				exit;
			}
			$data['stream_end_time'] = 0;
			$data['update_time'] = time();
			$updateChannel = Dao::factory('Channel')->updateChannel($data, $channelIds);
		}
		
		return true;
	}
	/**
	 * 流断开验证并设置断开时间
	 * @param  string $name 流标识
	 * @return header 200ok 500断开
	 */
	public function onPublishDoneVerifyInput($name) {
		if (strlen($name) != 32) {
			Header("HTTP/1.1 500");
			exit;
		}
		$channels = Dao::factory('Channel')->getChannelByPushStreamId($name);

		if (!$channels->count()) {
			Header("HTTP/1.1 500");
			exit;
		}

		$channelIds = [];
		//判断是否被删除 删除就要断
		foreach ($channels as $channel) {
			$channelIds[] = $channel->channel_id;
		}
		$data['stream_end_time'] = time();
		$data['update_time'] = time();
		$updateChannel = Dao::factory('Channel')->updateChannel($data,$channelIds);
		if (!$updateChannel) {
			Header("HTTP/1.1 500");
			exit;
		}
		return true;
	}
	
	/**
	 * 播放验证
	 * @param  string $name token流标识
	 * @return header 200ok 500 失败
	 */
	public function onPlayVerify($name) {
		if (strlen($name) != 32) {
			Header("HTTP/1.1 500");
			exit;
		}
		
		$channels = Dao::factory('Channel')->getChannelByPlayStreamId($name);

		if (!$channels->count()) {
			Header("HTTP/1.1 500");
			exit;
		}
		return true;
	}
	/**
	 * 移动直播申请流地址
	 * @param  申请参数 $flowData 
	 * @param  [type] $type     [description]
	 * @return [type]           [description]
	 */
	public function getMobileFlow($flowData,$type) {
		//申请参数验证
		$flowsInformation = $this->_verifyFlowsData($flowData);
		//获取推流cdn
		$cdnPush = $this->getCdnPush($type);
		// 获取微妙
		$microtime = $this->microtime_float();
		$pushStreamId = md5($flowsInformation['start_time'].$microtime.'push'.uniqid());
		$playStreamId = md5($flowsInformation['start_time'].$microtime.'play'.uniqid());
		//申请输出流地址
		if (isset($flowsInformation['forward']) || !empty($flowsInformation['forward'])) {
			$flows['0']['forward'] = json_encode($flowsInformation['forward']);
		}
		if (isset($flowsInformation['warm_input']) || !empty($flowsInformation['warm_input'])) {
			$flows['0']['warm_input'] = $flowsInformation['warm_input'];
		}

		$flows['0']['input'] = $cdnPush['rtmp'];
		if (isset($flowsInformation['protocol']) || !empty($flowsInformation['protocol'])) {
			$flows['0']['protocol'] = $flowsInformation['protocol'];
		}

		$flows['0']['type'] = $flowsInformation['type'];

		if (isset($flowsInformation['is_record']) || !empty($flowsInformation['is_record'])) {
			$flows['0']['is_record'] = $flowsInformation['is_record'];
		}
		
		$flows['0']['clarity'] = $flowsInformation['clarity']['0'];
		$flows['0']['push_stream_id'] = $pushStreamId;
		$flows['0']['play_stream_id'] = $playStreamId;
		
		$flows['0']['start_time'] = $flowsInformation['start_time'];
		$flows['0']['create_time'] = time();
		//组合流地址
		$port = isset($flowsInformation['port']) ? ':'.$flowsInformation['port'] : '';
		$flowAddress['input'] = $flowsInformation['protocol'].$cdnPush['rtmp'].$port.'/live/'.$pushStreamId;
		echo '<pre>';
		print_r($flows);
		print_r($flowAddress);
		exit;
		
	}

	public function getCdnPush($type) {
		$httpUri = Kohana::$config->load('uri.cdn.getCdnPush');
		$http = new Curl_Request();
		$httpResponse = $http->get($httpUri."type=".$type);
		$results = json_decode($httpResponse, true);
		if ($results['message'] != '') {
			throw new Business_Exception('移动直播推申请失败');
		}
		return $results['data'];
	}

	public function getCdnPlay($rtmp_push) {
		$httpUri = Kohana::$config->load('uri.cdn.getCdnPlay');
		$http = new Curl_Request();
		$httpResponse = $http->get($httpUri."rtmp_push=".$rtmp_push);
		$results = json_decode($httpResponse, true);
		if ($results['message'] != '') {
			throw new Business_Exception('移动直播申请失败');
		}
		return $results['data'];
	}

	private function _verifyFlowsData($flowsInformation) {
		if (empty($flowsInformation)) {
			throw new Business_Exception('参数不能为空');
		}
		$error = array();
		if (empty($flowsInformation['model'])) {
			$error[] = '流工作模式不能为空 ';
		}
		if (empty($flowsInformation['clarity'])) {
			$error[] = '清晰度不能为空 ';
		}
		if (empty($flowsInformation['protocol'])) {
			$error[] = '协议不能为空 ';
		}
		if (empty($flowsInformation['is_convert'])) {
			$error[] = '是否转码不能为空 ';
		}
		if (empty($flowsInformation['is_record'])) {
			$error[] = '是否录制不能为空 ';
		}
		if (empty($flowsInformation['is_review'])) {
			$error[] = '是否回看不能为空 ';
		}
		if (empty($flowsInformation['start_time'])) {
			$error[] = '直播开始时间不能为空 ';
		}
		if (!empty($error)) {
			throw new Business_Exception(implode(' ', $error));
		}
		return $flowsInformation;
	}
}