<?php

/**
 * app过滤
 * 验证app是否存在
 * @package default
 * @author  qichangchun<qichangchun@gomeplus.com>
 * @date:   2017/2/7
 * @time:   10:32
 */
class App extends Abstract_Filter {

	private static $appList = array();

	/**
	 * 验证当前app,appkey是否正确
	 * @param $url
	 */
	public function auth() {
		$exec = Kohana::$config->load('default.api.exec');
		if (!$exec) {
			return true;
		}
		self::getAppList();
		if (!self::$appList) {
			throw new Exception("empty app lists");
		}
		$referer = $_SERVER['SERVER_NAME'] . $_SERVER['PATH_INFO'] . '?' . $_SERVER['QUERY_STRING'];
		$queryParam = self::parseUrl($referer);
		if (!$queryParam) {
			throw new Exception("the referer incorrect");
		}
		if (!isset($queryParam['appname']) || !isset($queryParam['token'])) {
			throw new Exception("request param error");
		}
		if (!self::_authorize($queryParam['token'], $queryParam['appname'])) {
			throw new Exception("app access denied");
		}
	}

	private static function _authorize($token, $app) {
		$appExist = false;
		foreach (self::$appList as $value) {
			if (strtolower($app) == strtolower($value['appname'])) {
				$appKey = $value['appkey'];
				$appExist = true;
			}
		}
		if (!$appExist) {
			throw new Exception("app not exists");
		}
		try {
			$expireTime = Business::factory('Aes')->decrypt($token, $appKey);
			if(!$expireTime){
				throw new Exception("token error");
			}
			$array = explode("|", $expireTime);
			list ($s1, $s2) = explode(' ', microtime());
			$time_now = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
			$expire = Kohana::$config->load("default.api.expire");
			if (!isset($array[0]) || !isset($array[1])) {
				throw new Exception("token error");
			}
			if (($time_now - $expire) > $array[0]) {
				throw new Exception("token has be expired");
			}
			if ($app != $array[1]) {
				throw new Exception("token not match the appname");
			}

			//验证是否存在
			$redis = Redisd::instance('api');
			if ($redis->get($token)) {
				throw new Exception("token exists");
			}
			$redis->set($token, 1);
		} catch (Exception $e) {
			throw $e;
		}
		return true;
	}

	public static function parseUrl($url) {
		$urlParam = $queryParam = array();
		if (!$url || !is_string($url)) {
			return false;
		}
		try {
			$urlParam = parse_url($url);
		} catch (Exception $e) {
			return false;
		}
		if (isset($urlParam['query'])) {
			parse_str($urlParam['query'], $queryParam);
			return $queryParam;
		} else {
			return false;
		}
	}

	public static function getAppList() {
		if (self::$appList) {
			return self::$appList;
		}
		self::$appList = Business::factory('app')->getList();
		return self::$appList;
	}

	public static function exist($appName, $appKey) {
		$exist = false;
		$appList = self::getAppList();
		foreach ($appList as $app) {
			if ($appName == $app['appname'] && $appKey == $app['appkey']) {
				$exist = true;
			}
		}
		if (!$exist) {
			throw new Exception("app and appKey not match");
		}
	}
}