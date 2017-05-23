<?php

/**
 * reids缓存操作类
 * @author pengmeng<pengmeng@gomeplus.com>
 */
class Redisd {

	protected static $_instance = NULL, $driver = '';
	protected static $_instanceSelf = null;

	/*
	 * @return Redisd
	 */

	public static function instance($type, $force = false) {
		if (self::$_instanceSelf === NULL || $force) {
			self::$_instanceSelf = new Redisd($type, $force);
		}
		return self::$_instanceSelf;
	}

	public function __construct($type, $force) {
		$config = Kohana::$config->load('redisd.' . $type);
		if (empty($config)) {
			throw new Exception('redisd config group [ ' . $type . ' ] not found');
		}
		if ($config['type'] == 'redis') {
			self::$driver = 'redis';
		} elseif ($config['type'] == 'cluster') {
			self::$driver = class_exists('RedisCluster', false) ? 'cluster' : 'redis';
		}
		switch (self::$driver) {
			case 'cluster':
				if (self::$_instance === NULL || $force) {
					self::$_instance = new RedisCluster(null, $config['cluster']);
				}
				break;
			case 'redis':
				if (self::$_instance === NULL || $force) {
					$redis = new Redis();
					$server = $config['redis'];
					if ($server['persistent']) {
						$redis->pconnect($server['host'], $server['port'], $server['timeout']);
					} else {

						$redis->connect($server['host'], $server['port'], $server['timeout']);
					}
					if ($server['password'] !== NULL) {
						$result = $redis->auth($server['password']);
						if (strtolower($result) != true && strtolower($result) != 'ok') {
							throw new Exception("Invaild redis [ " . $type . " ] password: {$server['password']}");
						}
					}
					self::$_instance = $redis;
				}
				break;
			default:
				break;
		}
	}

	public function get($key) {
		$data = self::$_instance->get($key);
		return @unserialize($data);
	}

	public function setnx($key,$value) {
		return self::$_instance->setnx($key,$value);
	}

	public function delete($key) {
		return self::$driver == 'redis' ? self::$_instance->delete($key) : self::$_instance->del($key);
	}

	public function set($key, $value, $expire = 0) {
		$value = @serialize($value);
		if ($expire) {
			return self::$_instance->setex($key, $expire, $value);
		} else {
			return self::$_instance->set($key, $value);
		}
	}

	public function hSet($hashKey, $key, $value) {
		return self::$_instance->hSet($hashKey, $key, $value);
	}

	public function expire($key, $expire) {
		if (method_exists(self::$_instance, 'setTimeout')) {
			return self::$_instance->setTimeout($key, $expire);
		} else {
			return self::$_instance->expire($key, $expire);
		}
	}

	public function hGet($hashKey, $key) {
		return self::$_instance->hGet($hashKey, $key);
	}

	public function hDel($hashKey, $key) {
		return self::$_instance->hDel($hashKey, $key);
	}

	public function hGetAll($hashKey) {
		return self::$_instance->hGetAll($hashKey);
	}

	public function mget($keys) {
		if (!is_array($keys) || empty($keys)) {
			return false;
		}
		//nil默认处理为false
		$values = self::$_instance->mget($keys);
		return array_combine($keys, $values);
	}

	public function lget($key) {
		$listLength = self::$_instance->lLen($key);
		if (!$listLength) {
			return false;
		}
		return self::$_instance->lRange($key, 0, $listLength);
	}

	public function lpush($key, $value) {
		return self::$_instance->lPush($key, $value);
	}

}
