<?php
/**
 * 日志类
 * @author baishen
 */
class Logger {

	static protected $_instance = NULL;

	static protected $_writer = NULL;
	
	
	/**
	 * 增加日志，不直接写入
	 * @param $message
	 */
	static public function add($message) {
		Logger::instance()->message($message);
	}

	/**
	 * 写入
	 */
	static public function write($message) {
		Logger::instance()->message($message, TRUE);
	}

	/**
	 * 单例
	 * @param array $config
	 */
	static public function instance() {
		if(self::$_instance === NULL) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 日志
	 * @var array
	 */
	protected $_messages = array();
	
	public function __construct() {
		$type = Kohana::$config->load('logger.type');
		self::$_writer = Logger_Writer::factory($type);
	}

	/**
	 * 增加日志消息
	 * @param array $message
	 * @param boolean $writeNow
	 */
	public function message($message, $writeNow = FALSE) {

		$this->_messages[] = array (
			'portal' => PORTAL,
			'controller' => strtolower(Request::current()->controller()),
			'action' => strtolower(Request::current()->action()),
			'get' => json_encode($_GET),
			'post' => json_encode($_POST),
			'message' => $message,
			'ip' => Misc::getClientIp(),//Request::$client_ip
			'referer' => Request::current()->referrer(),
			'user_agent' => Request::$user_agent,
			// 'account_id' => Author::instance()->accountId(),  
			// 'account_name' => Author::instance()->name(),  
			'create_time' => time(),
		);

		if(!empty($custom = Kohana::$config->load('logger.custom'))) {
			$this->_messages= array(array_merge($this->_messages[0], $custom));
		}

		if($writeNow === TRUE) {
			$this->_write();
		}
		
		return $this;
	}
	
	/**
	 * 写入
	 */
	protected function _write() {
		if(!$this->_messages) {
			return TRUE;
		}

		$messages = $this->_messages;
		$this->_messages = array();

		$faileds = array();
		foreach($messages as $message) {
			$result = self::$_writer->write($message);
			if(!$result[0]) {
				$faileds[] = $message;
			}
		}
		if($faileds) {
			throw new Logger_Exception('日志写入失败:'. implode("&nbsp;\n", $faileds));
		}
		return TRUE;
	}
	
	/**
	 * 析构，写入日志
	 */
	public function __destruct() {
		$this->_write();
	}
}