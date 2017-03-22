<?php
/**
 * 查找浏览器访问入口类型
 * @date 2016-07-05
 * @see discuz3x
 * @author quanhengzhe(quanhengzhe@gomeplus.com)
 */
class Agent {
	
	protected static $_instance = '';
	//pc浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
	private $_brower = array(
								'mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 
								'openwave', 'myop'
							);
	//触控浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
	private $_touchbrowser =array(
								'iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 
								'java', 'opera mobi', 'opera mini','ucweb', 'windows ce', 
								'symbian', 'series', 'webos', 'sony', 'blackberry',
								'dopod', 'nokia', 'samsung','palmsource', 'xda', 
								'pieplus', 'meizu','midp', 'cldc', 'motorola', 'foma', 
								'docomo', 'up.browser','up.link', 'blazer', 'helio', 
								'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 
								'techfaith', 'palmsource','alcatel', 'amoi', 'ktouch', 
								'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 
								'bunjalloo', 'maui', 'smartphone','iemobile', 'spice', 
								'bird', 'zte-', 'longcos', 'pantech', 'gionee', 
								'portalmmm', 'jig browser', 'hiptop','benq', 'haier', 
								'^lct', '320x320', '240x320', '176x220'
							);
	//wap浏览器中$_SERVER['HTTP_USER_AGENT']所包含的字符串数组
	private $_wmlbrowser = array(
								'cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 
								'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom','pantech', 
								'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 
								'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh','sed', 
								'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 
								'panda', 'zte'
							);

	private function __construct($param = array()) {

	}
	/**
	 * 单例模式-内部实例化
	 */
	static public function instance($param = array()) {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self($param);
		}
		return self::$_instance;
	}
	/**
	 * 判断访问地址是哪一种浏览器类型
	 * @return string
	 */
	public function checkMobile() {
		$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'mozilla';
		$userAgent = strtolower($agent);
		
		if ($this->searchType($userAgent, $this->_touchbrowser)) {
			return 'h5';
		}

		if ($this->searchType($userAgent, $this->_wmlbrowser)) {
			return 'h5';
		}
		if ($this->searchType($userAgent, $this->_brower)) {
			return 'pc';
		}
		return 'pc';
	}
	/**
	 * 查找某字符在HTTP_USER_AGENT字符串中首次出现位置
	 * @param  string $userAgent
	 * @param  array  $mobileType
	 * @return bool
	 */
	protected function searchType($userAgent, array $mobileType) {
		if(empty($userAgent)) {
			return false;
		}
		foreach((array)$mobileType as $value) {
			if(strpos($userAgent, $value) !== false) {
				return true;
			}
		}
		return false;
	
	}
	/**
	 * 防止克隆
	 */
	public function __clone() {
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}
}