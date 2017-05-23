<?php
/**
 * 消息队列
 * @author: panchao
 */
abstract class MQ {

	/**
	 * MQ config
	 * @var array
	 */
	protected static $_config = [];

	/**
	 * MQ name
	 * @var string
	 */
	protected $_name = '';

	/**
	 * MQ data
	 * @var array
	 */
	protected $_data = [];

	/**
	 * MQ data format default json
	 * @var string
	 */
	protected $_format = QFormat::FORMAT_JSON;

	/**
	 * factory
	 * @param string $type
	 * @param array $config
	 * @return mixed
	 * @throws MQ_Exception
	 */
	public static function factory($type = 'wmq', $config = []) {
		$type = ucfirst(strtolower($type));

		$className = "MQ_". $type;
		if(!class_exists($className)) {
			throw new MQ_Exception('Not support MQ way');
		}

		if(!$config) {
			$config = Kohana::$config->load('mq.'.strtolower($type));
		}

		self::$_config = $config;

		return call_user_func(array($className, 'instance'));
	}

	/**
	 * WMQ constructor.
	 */
	public function __construct() {}

	/**
	 * MQ name
	 * @param $name
	 * @return $this
	 */
	public function name($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * MQ data
	 * @param $data
	 * @return $this
	 */
	public function data($data) {
		$this->_data = $data;
		return $this;
	}

	/**
	 * MQ data type
	 * @param int|string $format
	 * @return $this
	 */
	public function format($format = QFormat::FORMAT_JSON) {
		$this->_format = $format;
		return $this;
	}

	/**
	 * data json
	 * @return $this
	 */
	public function asJson() {
		$this->_format = QFormat::FORMAT_JSON;
		return $this;
	}

	/**
	 * data xml
	 * @return $this
	 */
	public function asXML() {
		$this->_format = QFormat::FORMAT_XML;
		return $this;
	}

	/**
	 * data json
	 * @return $this
	 */
	public function asArray() {
		$this->_format = QFormat::FORMAT_ARRAY;
		return $this;
	}

	abstract function send();

}