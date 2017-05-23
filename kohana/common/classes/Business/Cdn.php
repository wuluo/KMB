<?php
/**
 * CDN 业务逻辑
 * @author: panchao
 */
class Business_Cdn extends Business {

	/**
	 * 创建cdn
	 * @param $values
	 * @throws Business_Exception
	 */
	public function create($values) {

		$fields = [
			'cdn_name' => '',
			'rtmp_push' => '',
			'rtmp_play' => '',
			'hls_play' => '',
			'weight' => '',
			'is_default' => Dao_Cdn::IS_DEFAULT_FALSE,
			'is_delete' => Dao_Cdn::IS_DELETE_FALSE,
			'create_time' => time(),
			'update_time' => time(),
		];

		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$cndName = Arr::get($values, 'cdn_name', '');
		$rtmpPush = Arr::get($values, 'rtmp_push', '');
		$rtmpPlay = Arr::get($values, 'rtmp_play', '');
		$hlsPlay = Arr::get($values, 'hls_play', '');
		$weight = Arr::get($values, 'weight', '');

		$errors = array ();
		if(!$cndName) {
			$errors[] = '厂商名称不能为空';
		}
		if(!$rtmpPush) {
			$errors[] = 'rtmp推流域名不能为空';
		}
		if(!$rtmpPlay) {
			$errors[] = 'rtmp播放域名不能为空';
		}
		if(!$hlsPlay) {
			$errors[] = 'hls播放域名不能为空';
		}
		if(!$weight || !is_numeric($weight) || $weight < 1 || $weight > 100) {
			$errors[] = '权重必须为1-100数字';
		}
		if($errors) {
			throw new Business_Exception($errors[0]);
		}

		$cdns = Dao::factory('Cdn')->getCdnByCdnName($cndName);
		if($cdns->count()) {
			throw new Business_Exception('厂商名称已经存在');
		}

		return Dao::factory('Cdn')->insert($values);

	}

	/**
	 * 根据 cdn_id 查找 cdn
	 * @param $cdnId
	 */
	public function getCdnByCdnId($cdnId) {
		return Dao::factory('Cdn')->getCdnByCdnId($cdnId);
	}

	/**
	 * 根据 cdn_id 来修改cdn
	 * @param $values
	 * @param $cdnId
	 * @throws Business_Exception
	 */
	public function updateByCdnId($values, $cdnId) {

		$fields = [
			'cdn_name' => '',
			'rtmp_push' => '',
			'rtmp_play' => '',
			'hls_play' => '',
			'weight' => 0,
			'is_default' => Dao_Cdn::IS_DEFAULT_FALSE,
		];

		$values = array_intersect_key($values, $fields);
		$values = $values + $fields;

		$cndName = Arr::get($values, 'cdn_name', '');
		$rtmpPush = Arr::get($values, 'rtmp_push', '');
		$rtmpPlay = Arr::get($values, 'rtmp_play', '');
		$hlsPlay = Arr::get($values, 'hls_play', '');
		$weight = Arr::get($values, 'weight', '');

		$errors = array ();
		if(!$cndName) {
			$errors[] = '厂商名称不能为空';
		}
		if(!$rtmpPush) {
			$errors[] = 'rtmp推流域名不能为空';
		}
		if(!$rtmpPlay) {
			$errors[] = 'rtmp播放域名不能为空';
		}
		if(!$hlsPlay) {
			$errors[] = 'hls播放域名不能为空';
		}
		if(!$weight || !is_numeric($weight) || $weight < 1 || $weight > 100) {
			$errors[] = '权重必须为1-100数字';
		}
		if($errors) {
			throw new Business_Exception($errors[0]);
		}

		$cdns = Dao::factory('Cdn')->getCdnByCdnName($cndName);
		if($cdns->count() > 1 || ($cdns->count() == 1 && $cdns->current()->cdn_name != $cndName)) {
			throw new Business_Exception('厂商名称已经存在');
		}
		$values['update_time'] = time();

		return Dao::factory('Cdn')->updateByCdnId($values, $cdnId);
	}

	/**
	 * 根据 cdn_id 来删除cdn
	 * @param $cdnId
	 * @return bool
	 */
	public function deleteByCdnId($cdnId) {

		if(!$cdnId) {
			return FALSE;
		}

		$values = [
			'is_delete' => Dao_Cdn::IS_DELETE_TRUE,
			'update_time' => time(),
		];

		return Dao::factory('Cdn')->updateByCdnId($values, $cdnId);
	}

	/**
	 * 根据关键字查找cdn数量
	 * @param $keywords
	 */
	public function countCdnsByKeywords($keywords) {
		return Dao::factory('Cdn')->countCdnsByKeywords($keywords);
	}

	/**
	 * 根据关键字分页获取cdn
	 * @param $keywords
	 * @param $offset
	 * @param $number
	 */
	public function getCdnsByKeywordsAndLimit($keywords, $offset, $number) {
		return Dao::factory('Cdn')->getCdnsByKeywordsAndLimit($keywords, $offset, $number);
	}

	/**
	 * 获取 cdn 总数
	 */
	public function countCdns() {
		return Dao::factory('Cdn')->countCdns();
	}

	/**
	 * 分页获取 cdn
	 * @param $offset
	 * @param $number
	 */
	public function getCdnsByLimit($offset, $number) {
		return Dao::factory('Cdn')->getCdnsByLimit($offset, $number);
	}

	/**
	 * 根据 rtmp_push 获取cdn
	 * @param $rtmpPush
	 */
	public function getCdnByRtmpPush($rtmpPush) {
		return Dao::factory('Cdn')->getCdnByRtmpPush($rtmpPush);
	}

	/**
	 * 根据 is_default 获取cdn
	 * @param $isDefault
	 */
	public function getCdnByIsDefault($isDefault) {
		return Dao::factory('Cdn')->getCdnByIsDefault($isDefault);
	}

	/**
	 * 根据多个 cdn_id 来查找 cdn
	 * @param $cdnIds
	 * @return array
	 */
	public function getCdnByCdnIds($cdnIds = []) {
		if(empty($cdnIds)) {
			return [];
		}
		return Dao::factory('Cdn')->getCdnByCdnIds($cdnIds);
	}

	/**
	 * 根据 input 地址获取 cdn 配置
	 * 若无配置，返回默认的CDN
	 * @param $input
	 */
	public function getCdnSettingByInput($input) {

		$cdnStreams = Dao::factory('Cdn_Stream')->getCdnByInput($input);
		if(!$cdnStreams || !$cdnStreams->count()) {
			$cdns = Dao::factory('Cdn')->getCdnByIsDefault(Model_Cdn::IS_DEFAULT_TRUE);
		}else {
			$cdnIds = [];
			foreach ($cdnStreams as $cdnStream) {
				$cdnIds[] = $cdnStream->cdn_id;
			}
			$cdns = Business::factory('Cdn')->getCdnByCdnIds($cdnIds);
		}
		return $cdns;
	}

	/**
	 * 获取自有CDN机器列表
	 */
	public function getSelfCdn() {

		$httpUri = Kohana::$config->load('uri.machine.getApplicationMachine');
		$http = new Curl_Request();
		$httpResponse = $http->get($httpUri."service=stream&group=stream");
		$results = json_decode($httpResponse, true);
		return $results['data'];
		
	}

	/**
	 * 获取最优的 CDN
	 */
	public function getBestSelfCdn() {

		$httpUri = Kohana::$config->load('uri.machine.getApplicationMachine');
		$http = new Curl_Request();
		$httpResponse = $http->get($httpUri."service=stream&group=stream&n=1");
		$results = json_decode($httpResponse, true);
		return $results['data'];
	}
}