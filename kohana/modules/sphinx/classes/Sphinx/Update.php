<?php
/**
 * Sphinx 更新索引
 * @author: panchao
 * Sphinx_Update::instance()
 *          ->data($data)
 *          ->execute()
 */
class Sphinx_Update {

	private static $_instance = NULL;

	protected $_updateApi = '';

	protected $_data = [];

	/**
	 * @param $sphinx
	 * @return null|Sphinx_Update
	 */
	public static function instance($sphinx) {

		$sphinx = strtolower($sphinx);
		$config = Kohana::$config->load('sphinx.' . $sphinx);

		self::$_instance = new self($config);

		return self::$_instance;
	}

	public function __construct($config) {
		$this->_updateApi = Arr::get($config, 'update_api', '');
	}

	/**
	 * data
	 * @param array $data
	 * @return $this
	 */
	public function data(array $data) {
		$this->_data = $data;
		return $this;
	}

	/**
	 * execute
	 * @throws Sphinx_Exception
	 */
	public function execute() {

		if($this->_updateApi == '') {
			throw new Sphinx_Exception('sphinx config update_api error');
		}

		$http = new Curl_Request();
		$http->post($this->_updateApi, $this->_data);
		if($http->code() != '200') {
			throw new Sphinx_Exception('更新索引信息失败, 错误地址'. $this->_updateApi . ' 原因：' . $http->data());
		}

		return TRUE;
	}

}