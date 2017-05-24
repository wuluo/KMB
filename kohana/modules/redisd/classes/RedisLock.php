<?php

/**
 * Redis分布锁实现
 * 实例:
 * 初始化并配置锁
 * $lock = new RedisLock(Sr::cache()->instance());
 *  $lock->setKeyPrefix('testLock:')->setRxpire(15);
 * 使用锁:
 * $siteLockKey = 'site-lock';
 * if (!$lock->lock($siteLockKey)) {
 * 	throw new Exception(Sr::json(10013));
 * }
 */
class RedisLock {

	private $_keyPrefix = 'RedisLock:';
	private $_retryCount = 0; //重试次数,0:无限重试
	private $_retryIntervalUs = 100000; //获取锁失败重试等待的时间,微妙 ,1ms=1000us
	private $_retry = true; //获取锁失败,是否重试
	private $_expire = 0; //锁的超时时间,防止死锁发生,应该是业务的最大处理时间
	private $_redis = null;//redis类的实例对象
	private $_lockKey = '';

	public function __construct($_redisHandle, $_keyPrefix = 'RedisLock:', $_expire = 10, $_retry = true, $_retryCount = 0, $_retryIntervalUs = 100000) {
		$this->setKeyPrefix($_keyPrefix);
		$this->_expire = $_expire;
		$this->_retry = $_retry;
		$this->_retryCount = $_retryCount;
		$this->_retryIntervalUs = $_retryIntervalUs;
		$this->_redis = $_redisHandle;
	}

	public function lock($key) {
		$this->_lockKey = $this->_keyPrefix . $key;
		$count = 0;
		while (true) {
			$nowTime = self::microtime();
			$lockValue = self::microtime() + $this->_expire;
			$lock = $this->_redis->setnx($this->_lockKey, $lockValue);
			if (!empty($lock) || ($this->_redis->get($this->_lockKey) < $nowTime && $this->_redis->getset($this->_lockKey, $lockValue) < $nowTime )) {
				$this->_redis->expire($this->_lockKey, $this->_expire);
				return true;
			} elseif ($this->_retry && (($this->_retryCount > 0 && ++$count < $this->_retryCount) || ($this->_retryCount == 0))) {
				usleep($this->_retryIntervalUs);
			} else {
				break;
			}
		}
		return false;
	}

	public function unlock($lockKey) {
		if ($this->_redis->ttl($lockKey)) {
			method_exists($this->_redis, 'del') ? $this->_redis->del($lockKey) : $this->_redis->delete($lockKey);
		}
	}

	public function &getRedis() {
		return $this->_redis;
	}

	public function setRedis($_redis) {
		$this->_redis = $_redis;
		return $this;
	}

	public function getKeyPrefix() {
		return $this->_keyPrefix;
	}

	public function getRetryCount() {
		return $this->_retryCount;
	}

	public function getRetryIntervalUs() {
		return $this->_retryIntervalUs;
	}

	public function getRetry() {
		return $this->_retry;
	}

	public function getExpire() {
		return $this->_expire;
	}

	public function setKeyPrefix($_keyPrefix) {
		$this->_keyPrefix = $_keyPrefix == rtrim($_keyPrefix, ':') ? $_keyPrefix . ':' : $_keyPrefix;
		return $this;
	}

	public function setRetryCount($_retryCount) {
		$this->_retryCount = $_retryCount;
		return $this;
	}

	public function setRetryIntervalUs($retryIntervalUs) {
		$this->_retryIntervalUs = $retryIntervalUs;
		return $this;
	}

	public function setRetry($_retry) {
		$this->_retry = $_retry;
		return $this;
	}

	public function setRxpire($_expire) {
		$this->_expire = $_expire;
		return $this;
	}

	/**
	 * 获取当前UNIX毫秒时间戳
	 * @return float
	 */
	static function microtime() {
		// 获取当前毫秒时间戳
		list ($s1, $s2) = explode(' ', microtime());
		$currentTime = (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		return $currentTime;
	}

}
