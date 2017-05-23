<?php

/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/7
 * @time:   11:20
 */
class Business_App extends Business {

	public function getList() {
		$appList = array();
		$redis = Redisd::instance('api');
		if (!$appListJson = $redis->get(Cache_Cache::app())) {
			$appList = Dao::factory('App')->getList();
			$redis->set(Cache_Cache::app(), json_encode($appList), Cache_Expired::app());
		} else {
			$appList = json_decode($appListJson, true);
		}
		return $appList;
	}
}