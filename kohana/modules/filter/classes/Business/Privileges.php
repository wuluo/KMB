<?php

/**
 * desc
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/10
 * @time:   14:02
 */
class Business_Privileges extends Business {

	public function getPrivilege() {
		$redis = Redisd::instance('api');
		$privilege = array();
		if ($privilege = $redis->get(Cache_Cache::privilege())) {
			return json_decode($privilege, true);
		}
		$privilege = Dao::factory('Privileges')->getList();
		if ($privilege) {
			$redis->set(Cache_Cache::privilege(), json_encode($privilege), Cache_Expired::privilege());
		}
		return $privilege;
	}

	public function getPrivilegeByAppId($appId) {
		$appPrivileges = Dao::factory('Privileges')->getPrivilegeByAppId($appId);
		return $appPrivileges;
	}
}