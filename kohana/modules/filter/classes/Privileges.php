<?php

/**
 * app权限验证
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/10
 * @time:   12:28
 */
class Privileges extends Abstract_Filter {
	public static $privilege = array();

	public function auth() {
		$exec = Kohana::$config->load('default.api.exec');
		if (!$exec) {
			return true;
		}
		$referer = $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO'] . '?' . $_SERVER['QUERY_STRING'];
		$queryParam = App::parseUrl($referer);
		if (!$queryParam || !isset($queryParam['appname'])) {
			throw new Exception("the referer incorrect");
		}
		//获取权限对应列表
		$avePrivilege = self::getPrivilegeByApp($queryParam['appname']);
		if (!$avePrivilege) {
			return true;
		}
		//获取当前协议
		if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
			$urlType = 'https';
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
			$urlType = 'https';
		} elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
			$urlType = 'https';
		} else {
			$urlType = 'http';
		}
		if (!self::authIn($urlType . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO'], $queryParam, $_POST, $avePrivilege)) {
			throw new Exception("access denied");
		}
		return true;
	}

	public static function getPrivilege() {
		return Business::factory('Privileges')->getPrivilege();
	}

	public static function getPrivilegeByApp($app) {
		$cacheKey = Cache_Cache::appPrivilege();
		$privliege = Redisd::instance('api')->hGet($cacheKey, $app);
		if (!$privliege) {
			$appId = 0;
			$formatPrivilege = array();
			$apps = App::getAppList();
			if (!self::$privilege) {
				self::$privilege = self::getPrivilege();
			}
			if (!$apps) {
				throw new Exception("app not exist");
			}
			foreach ($apps as $as) {
				if (strtolower($as['appname']) == $app) {
					$appId = $as['app_id'];
				}
			}
			//获取对应关系
			$appPrivileges = Business::factory('Privileges')->getPrivilegeByAppId($appId);
			if ($appPrivileges) {
				$formatPrivilege['white'] = array();
				$formatPrivilege['black'] = array();
				foreach ($appPrivileges as $pri) {
					if ($pri['get']) {
						parse_str($pri['get'], $pri['get']);
					}
					if ($pri['post']) {
						parse_str($pri['post'], $pri['post']);
					}
					if ($pri['type'] == 1) {
						$formatPrivilege['white'][$pri['privilege_id']] = $pri;
					} else {
						$formatPrivilege['black'][$pri['privilege_id']] = $pri;
					}
				}
				Redisd::instance('api')->hSet($cacheKey, $app, json_encode($formatPrivilege));
				Redisd::instance('api')->expire($cacheKey, Cache_Expired::appPrivilege());
				return $formatPrivilege;
			}
		}
		return json_decode($privliege, true);
	}

	/**
	 * 筛选出权限
	 * @param $appPrivilege
	 */
	public static function authIn($hostSource, $getParam, $postParam, $appPrivilege) {
		unset($getParam['appname']);
		unset($getParam['appKey']);
		unset($getParam['token']);
		//黑名单存在，则不能访问黑名单
		if (isset($appPrivilege['black']) && !empty($appPrivilege['black'])) {
			foreach ($appPrivilege['black'] as $pri) {
				if ($pri['url'] != $hostSource) {
					continue;
				}
				$getBlackAccess = $postBlackAccess = true;
				if (!empty($getParam) && !empty($pri['get'])) {
					if ($getParam == $pri['get']) {
						$getBlackAccess = false;
					}
				} elseif (empty($pri['get'])) {
					$getBlackAccess = false;
				}

				if (!empty($postParam) && !empty($pri['post'])) {
					if ($postParam == $pri['post']) {
						$postBlackAccess = false;
					}
				} elseif (empty($pri['post'])) {
					$postBlackAccess = false;
				}

				if (!$getBlackAccess && !$postBlackAccess) {
					return false;
				}
			}
		}
		//白名单存在，只可以访问白名单
		if (isset($appPrivilege['white']) && !empty($appPrivilege['white'])) {
			foreach ($appPrivilege['white'] as $pri) {
				if ($pri['url'] != $hostSource) {
					continue;
				}
				$getWhiteAccess = $postWhiteAccess = false;
				if (!empty($getParam) && !empty($pri['get'])) {
					if ($getParam == $pri['get']) {
						$getWhiteAccess = true;
					}
				} elseif (empty($pri['get'])) {
					$getWhiteAccess = true;
				}

				if (!empty($postParam) && !empty($pri['post'])) {
					if ($postParam == $pri['post']) {
						$postWhiteAccess = true;
					}
				} elseif (empty($pri['post'])) {
					$postWhiteAccess = true;
				}

				if ($getWhiteAccess && $postWhiteAccess) {
					return true;
				}
			}
			return false;
		}
		return true;
	}
}