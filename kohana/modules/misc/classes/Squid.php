<?php
/**
 * 清除页面squid缓存
 * @author baishen
 *
 * $response = Squid::instance()
 * 				->playUrl($playUrl)
 * 				->execute()
 * 				->getResponse();
 */
class Squid {

	protected static $_instance = NULL;

	public static function instance() {
		self::$_instance = new self();

		return self::$_instance;
	}

	protected $_url = 'http://172.16.108.200/squid/squid_write_url.php';
	
	protected $_playUrl = '';

	protected $_response = '';

	/**
	 * @param string $url
	 * @return object
	 */
	public function playUrl($playUrl = '') {
		$this->_playUrl = $playUrl;
		return $this;
	}

	/**
	 * 执行
	 */
	public function execute() {
		$url = $this->_url.'?url='.$this->_playUrl;
		$handler = curl_init();
		curl_setopt($handler, CURLOPT_URL, $url);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, TRUE);
		$this->_response = curl_exec($handler);
		curl_close($handler);

		return $this;
	}

	/**
	 * 获取返回值
	 */
	public function getResponse() {
		return $this->_response;
	}
}
