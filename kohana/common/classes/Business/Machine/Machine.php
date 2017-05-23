<?php

/**
 * 机器操作逻辑
 * @author  quanhengzhe
 * @date:   2017/5/12
 * @time:   11:17
 */
class Business_Machine_Machine extends Business {

	private $_daoName = "Machine_Machine";

	private function _getOutIp($inIp, $defalt = "127.0.0.1") {
		$outIp = $defalt;
		$isIp = $this->_isIp($inIp);
		if ($isIp) {
			$machineList = Kohana::$config->load("machine.machine_list");
			if (is_array($machineList)) {
				foreach ($machineList as $m) {
					$outIp = $m['network_ip'] == $inIp ? $m['outside_ip'] : $outIp;
				}
			}
		}
		return $outIp;
	}

	private function _isIp($ip) {
		$ipFilter = filter_var($ip, FILTER_VALIDATE_IP);
		return $ipFilter;
	}

	/**
	 * 添加新机器
	 * @param $data
	 * @return mixed
	 * @throws Business_Exception ip_format
	 */
	public function addMachine($data) {
		if (!$this->_isIp($data['ip'])) {
			throw new Business_Exception("IP地址有误，无法添加新机器，IP地址为：" . $data['ip']);
		}
		return Dao::factory($this->_daoName)->addNewMachine($data);
	}

	/**
	 * 查找是否存在机器，返回in_ip,out_ip
	 * @param $inIp String 内网IP
	 * @return mixed
	 */
	public function searchMachineByInIp($inIp) {
		if (!$this->_isIp($inIp)) {
			throw new Business_Exception("IP地址有误,IP地址为：" . $inIp);
		}
		return Dao::factory($this->_daoName)->searchMachineByInIp('ip', $inIp);
	}

	/**
	 * 机器列表
	 * @param array $condition 条件数组
	 * @param string $logic 条件逻辑
	 * @param int $number 每页数量
	 * @param int $offset 起始
	 * @param string $orderBy 排序
	 * @param string $direction 排序规则
	 * @return mixed
	 */
	public function machineList($condition = array(), $logic = 'and', $number = 20, $offset = 0, $orderBy = "create_time", $direction = "desc") {
		return Dao::factory($this->_daoName)->machineList($condition, $logic, intval($number), intval($offset), $orderBy, $direction);
	}

	/**
	 * 删除指定IP的机器
	 * @param $inIp 内网ip
	 * @return mixed
	 */
	public function delMachine($inIp) {
		$updateData = array();
		$updateData['is_delete'] = 1;
		$updateData['is_available'] = 0;
		$updateData['update_time'] = time();
		return Dao::factory($this->_daoName)->modifyMachineData('ip', $updateData, $inIp);
	}

	/**
	 * 更新机器信息
	 * @param $inIp
	 * @param $updateData
	 * @return mixed
	 * @throws Business_Exception
	 */
	public function modifyMachine($id, $updateData) {
		if (!$updateData['ip']) {
			throw new Business_Exception('内网IP不能为空');
		}
		$pat = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))$/";
		if (!preg_match($pat, $updateData['ip'])) {
			throw new Business_Exception('内网IP格式不正确');
		}
		if ($updateData['out_ip']) {
			if (!preg_match($pat, $updateData['out_ip'])) {
				throw new Business_Exception('外网IP格式不正确');
			}
		}
		if (!is_numeric($updateData['multiway'])) {
			throw new Business_Exception('输入几路流数据必须数字');
		}
		if (!$updateData['service']) {
			throw new Business_Exception('所属服务不能为空');
		}
		if (!$updateData['machine_group']) {
			throw new Business_Exception('所属组不能为空');
		}
		$updateData['update_time'] = time();

		return Dao::factory($this->_daoName)->modifyMachineData('machine_id', $updateData, $id);
	}
	/**
	 * 分页
	 * @param  array   $condition
	 * @param  string  $logic
	 * @param  integer $size
	 * @param  string  $key
	 * @return object
	 */
	public function getPagination($condition = array(), $logic = "and", $size = 1, $key = 'page') {
		$count = Dao::factory($this->_daoName)->getConditionCount($condition, $logic);
		if (!$count) {
			$count = 0;
		}
		return Pagination::factory()
			->total($count)
			->number($size)
			->key($key)
			->execute();
	}

	/**
	 * 获取机器分组信息，配置文件
	 * @return array|Kohana_Config_Group
	 * @throws Kohana_Exception
	 */
	public function getServiceList() {
		$services = Kohana::$config->load('machine.service');
		if (is_array($services) && !empty($services)) {
			return $services;
		}
		return array();
	}
	/**
	 * 获取组
	 * @return array
	 */
	public function getGroupList() {
		$groups = Kohana::$config->load('machine.group');
		if (is_array($groups) && !empty($groups)) {
			return $groups;
		}
		return array();
	}
	/**
	 * 一旦删除或者修改为不可用删除redis 缓存
	 * @param  string $inIp
	 * @return bool
	 */
	public function deleteLiveRedis() {
		Redisd::instance('live')->delete(Cache_Key::machineTableCache());
	}
	/**
	 * 获取机器指标
	 * @param  string $ip 内网ip
	 * @return object
	 */
	public function getLogsByIp($ip) {
		$property = Dao::factory($this->_daoName)->searchMachineByInIp('ip' ,$ip, 'property');
		$property = $property->current();
		return $property;
	}
	/**
	 * 添加机器
	 * @param array $insertData 机器信息
	 * @return mixd
	 */
	public function addMachineDone($insertData) {
		if (!$insertData['ip']) {
			throw new Business_Exception('内网IP不能为空');
		}
		$pat = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))\.){3}((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))$/";
		if (!preg_match($pat, $insertData['ip'])) {
			throw new Business_Exception('内网IP格式不正确');
		}
		if ($insertData['out_ip']) {
			if (!preg_match($pat, $insertData['out_ip'])) {
				throw new Business_Exception('外网IP格式不正确');
			}
		}
		if (!is_numeric($insertData['multiway'])) {
			throw new Business_Exception('输入几路流数据必须数字');
		}
		if (!$insertData['service']) {
			throw new Business_Exception('所属服务不能为空');
		}
		if (!$insertData['machine_group']) {
			throw new Business_Exception('所属组不能为空');
		}
		
		$searchIp = Dao::factory($this->_daoName)->searchMachineByInIp('ip', $insertData['ip']);
		if ($searchIp->count()) {
			throw new Business_Exception('重复添加IP-'.$insertData['ip']);
		}
		$insertData['zipcode'] = Misc::getZipCodeByIp($insertData['ip']);
		$insertData['create_time'] = time();
		return Dao::factory($this->_daoName)->addNewMachine($insertData);
	}
	/**
	 * 机器上报,上报内网，外网ip某一个也支持
	 * @param  array $data
	 * @return mixd
	 */
	public function reportMachineStatus($data) {
		if (empty($data['network_ip']) && empty($data['outside_ip'])) {
			throw new Business_Exception('IP不能为空');
		}
		if (empty($data['process'])) {
			throw new Business_Exception('指标不能为空');
		}
		$data['event_time'] = empty($data['event_time']) ? time() : $data['event_time'];
		//查找内网ip是否存在
		if ($data['network_ip']) {
			$searchIp = Dao::factory($this->_daoName)->searchMachineByInIp('ip', $data['network_ip'], '*', 'is_delete');
			$searchIp = $searchIp->current();
			if (!$searchIp) {
				//不存在插入
				$insertData['ip'] = $data['network_ip'];
				if (!empty($data['outside_ip'])) {
					$insertData['out_ip'] = $data['outside_ip'];
				}
				$insertData['property'] = json_encode($data['process']);
				$insertData['zipcode'] = Misc::getZipCodeByIp($data['network_ip']);
				$insertData['create_time'] = time();
				$insert = Dao::factory($this->_daoName)->addNewMachine($insertData);
				if (!$insert) {
					throw new Business_Exception('上报失败001-'.$data['network_ip']);
				}
			} else {
				//存在就更新指标和时间
				if (!empty($data['outside_ip'])) {
					$updateData['out_ip'] = $data['outside_ip'];
				}
				$updateData['property'] = json_encode($data['process']);
				$updateData['update_time'] = time();
				//如果是删除的数据上报时恢复
				if ($searchIp->is_delete) {
					$updateData['is_delete'] = 0;
				}
				$update = Dao::factory($this->_daoName)->modifyMachineData('ip', $updateData, $data['network_ip']);
				
				if ($update == 0) {
					throw new Business_Exception('上报失败002-'.$data['network_ip']);
				}
			}
			return true;
		}
		//查找外网ip是否存折
		if ($data['outside_ip']) {
			$searchIp = Dao::factory($this->_daoName)->searchMachineByInIp('out_ip', $data['outside_ip'], '*', 'is_delete');
			$searchIp = $searchIp->current();
			if (!$searchIp) {
				//不存在插入
				if (!empty($data['network_ip'])) {
					$insertData['ip'] = $data['network_ip'];
				}
				$insertData['ip'] = $data['outside_ip'];
				$insertData['property'] = json_encode($data['process']);
				$insertData['zipcode'] = Misc::getZipCodeByIp($data['outside_ip']);
				$insertData['create_time'] = time();
				$insert = Dao::factory($this->_daoName)->addNewMachine($insertData);
				if (!$insert) {
					throw new Business_Exception('上报失败003-'.$data['outside_ip']);
				}
			} else {
				//存在就更新指标和时间
				if (!empty($data['network_ip'])) {
					$updateData['ip'] = $data['network_ip'];
				}
				$updateData['property'] = json_encode($data['process']);
				$updateData['update_time'] = time();
				//如果是删除的数据上报时恢复
				if ($searchIp->is_delete) {
					$updateData['is_delete'] = 0;
				}
				$updates = Dao::factory($this->_daoName)->modifyMachineData('out_ip', $updateData, $data['outside_ip']);
				if (!$updates) {
					throw new Business_Exception('上报失败004-'.$data['outside_ip']);
				}
			}
			return true;
		}
	}
	/**
	 * 生成机器缓存
	 * @return bool
	 */
	public function makeMachinesRedis() {
		
		$machines = Dao::factory($this->_daoName)->getMachines();
		$machines = $machines->as_array();
		$time = time();
		if (!empty($machines)) {
			foreach ($machines as $key => &$machine) {
				// $diffTime = $time - $machine['update_time'];
				// if ($diffTime > 8) {
				// 	unset($machine);
				// 	continue;
				// }
				$cacheData[$machine['service']][$machine['machine_group']][] = $machine;
			}
			Redisd::instance('live')->set(Cache_Key::machineTableCache(), json_encode($cacheData), Cache_Expire::machineTable());
		}
		return true;
	}
	/**
	 * 获取机器IP
	 * @param  string $service 服务
	 * @param  string $group   组
	 * @return array
	 */
	public function getApplicationMachine($service = 'stream', $group = 'stream', $num = 0) {
		//需要枷锁
		$cacheMachines = Redisd::instance('live')->get(Cache_Key::machineTableCache());
		if (!$cacheMachines) {
			$this->makeMachinesRedis();
			$cacheMachines = Redisd::instance('live')->get(Cache_Key::machineTableCache());
		}

		$machines = json_decode($cacheMachines,true);

		/**判断key是否存在**/
		if (!isset($machines[$service])) {
			throw new Business_Exception('服务不存在');
		}
		
		if (!isset($machines[$service][$group])) {
			throw new Business_Exception('服务组不存在');
		}
		/**判断key是否存在END**/
		//查出已分配的ip
		// $channel = Business::factory('Channel')->getAlreadyAssignedMachine();

		//最优指标数组
		$propertys = $this->getPropertyData($machines[$service][$group]);
		//调整的所有机器列表 最终拿到优的ip找内外网ip或其他数据返回
		$newMachines = array();
		
		foreach ($machines[$service][$group] as $key => $machineArr) {
			//机器表分组
			$newMachines[$machineArr['ip']] = $machineArr;
		}
		$highQualityMachines = array();
		foreach ($propertys as $k => $highQualityMachine) {
			$highQualityMachines[$k]['property'] = $highQualityMachine;
		}		
		//在最优的机器组中筛出对应可用机器
		if ($service == 'convert') {
			//编码器每个直播只需要一台
			foreach ($highQualityMachines as $qualityIp => $quality) {
				$channelCondition = 'encode_ip,push_stream_id';
				$channelMachineSum = Dao::factory('Channel')->getConvertIpSum($channelCondition, $qualityIp, 'push_stream_id');
				if ($channelMachineSum->count()) {
					$ffmpegNum = $channelMachineSum->count();
				} else {
					$ffmpegNum = 0;
				}
				// //判断一台机器同时能有几条工作
				if ($newMachines[$qualityIp]['multiway'] <= $ffmpegNum) {
					unset($highQualityMachines[$qualityIp]);
				}
			}
			
		} else {
			//其他服务可获取多台机器
			if ($num) {
				$highQualityMachines = array_slice($highQualityMachines, 0, $num);
			}
			//判断筛出的最优机器个数是否满足，用户请求个数
			$highQualityMachinesCount = count($highQualityMachines);
			if ($highQualityMachinesCount < $num) {
				throw new Business_Exception('申请' . $service . '服务， ' . $group . '组机器不足');
			}
		}

		if (empty($highQualityMachines)) {
			throw new Business_Exception('申请' . $service . '服务， ' . $group . '组机器失败');
		}
		$ips = array();
		foreach ($highQualityMachines as $ip => $highQuality) {
			$ips[$newMachines[$ip]['machine_id']]['network_ip'] = $newMachines[$ip]['ip'];
			$ips[$newMachines[$ip]['machine_id']]['outside_ip'] = $newMachines[$ip]['out_ip'];
		}
		
		//解锁end
		
		return array_values($ips);
		
	}

	/**
	 * 分最优机器组
	 * @param  array $machines 机器列表
	 * @return array
	 */
	public function getPropertyData($machines) {
		foreach ($machines as $key => $machineObj) {
			$machineArr = json_decode($machineObj['property'],true);
			unset($machineArr['fpm']);
			unset($machineArr['cpu']);
			unset($machineArr['memory']);
			//最优机器合集
			$propertys[$machineObj['ip']] = array_sum($machineArr);
			//指标最优的排序
			asort($propertys);			
		}
		return $propertys;
	}

}
