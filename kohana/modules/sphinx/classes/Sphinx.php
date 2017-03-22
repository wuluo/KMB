<?php
/**
 * 从sphinx获取数据
 *
 * try {
 *     $sphinx = Sphinx::instance()
 *          ->index(array('video_main', 'video_delta'))
 *          ->match(array('title', 'video_id'), 'test', TRUE)
 *          ->filter('account_id'), 1, '=')
 *          ->offset(0)
 *          ->limit(20)
 *          ->order('id', 'DESC')
 *          ->option('max_matches', $maxMatches)
 *          ->option('reverse_scan', 1)
 *          ->execute();
 *     $data = $sphinx->getData();
 *     $total = $sphinx->getTotal();
 *     $totalFound = $sphinx->getTotalFound();
 *     $time = $sphinx->getTime();
 * 
 * } catch(Exception $e) {
 * }
 */
class Sphinx {

	private static $_instance = NULL;
	
	public static function instance($search = NULL) {
		if($search) {
			$config = Kohana::$config->load('search.' . $search);
		} else {
			$config = Kohana::$config->load('search.default');
		}
		self::$_instance = new self($config);
		return self::$_instance;
	}

	private $_host = '';
	
	private $_uri = '';

	private $_url = '';

	private $_index = array('video_main', 'video_delta');

	private $_matches = array();

	private $_filters = array();

	private $_options = array();

	private $_orders = array();

	private $_group = '';

	private $_offset = 0;

	private $_limit = 20;

	private $_params = array();
	
	private $_result = array();
	
	private $_data = array();
	
	private $_total = 0;
	
	private $_totalFound = 0;
	
	private $_time = 0;
	
	public function __construct($config) {
		if(!isset($config['host'])) {
			throw new Sphinx_Exception('配置信息错误，“host”不存在');
		}
		if(!isset($config['uri'])) {
			throw new Sphinx_Exception('配置信息错误，“uri”不存在');
		}
		$this->_host = $config['host'];
		$this->_uri = $config['uri'];
	}

	/**
	 * 设置索引
	 * @param mixed $index
	 * @return object
	 */
	public function index($index = array()) {
		$this->_index = $index;
		return $this;
	}

	/**
	 * 设置匹配条件
	 * @param string $column
	 * @param string $value
	 * @param string $operator
	 * @return object
	 */
	public function match($column, $value, $half = FALSE) {
		$this->_matches[] = array (
				'column' => $column,
				'value' => $value,
				'half' => $half,
		);
		return $this;
	}

	/**
	 * 设置过滤条件
	 * @param string $column
	 * @param string $value
	 * @param string $operator
	 * @return object
	 */
	public function filter($column, $value, $operator = '=') {
		$this->_filters[] = array (
				'column' => $column,
				'value' => $value,
				'operator' => $operator,
		);
		return $this;
	}

	/**
	 * 设置选项
	 * @param string $name
	 * @param string $value
	 * @return object
	 */
	public function option($name, $value) {
		$this->_options[] = array (
				'name' => $name,
				'value' => $value,
		);
		return $this;
	}

	/**
	 * 设置分组
	 * @param string $group
	 * @return object
	 */
	public function group($group) {
		$this->_group = $group;
		return $this;
	}

	/**
	 * 设置排序
	 * @param string $column
	 * @param string $direction
	 * @return object
	 */
	public function order($column, $direction = 'DESC') {
		$this->_orders[] = array (
			'column' => $column,
			'direction' => $direction,
		);
		return $this;
	}

	/**
	 * 设置查询偏移量
	 * @param integer $offset
	 * @return object
	 */
	public function offset($offset = 0) {
		$this->_offset = $offset;
		return $this;
	}

	/**
	 * 设置查询数量
	 * @param integer $limit
	 * @return object
	 */
	public function limit($limit = 20) {
		$this->_limit = $limit;
		return $this;
	}

	/**
	 * 执行查询
	 * @return Search
	 */
	public function execute() {

		$params = array ();

		if($this->_index) {
			$params['index'] = $this->_index;	
		}
		if($this->_matches) {
			$params['matches'] = $this->_matches;
		}
		if($this->_filters) {
			$params['filters'] = $this->_filters;
		}
		if($this->_options) {
			$params['options'] = $this->_options;
		}
		if($this->_orders) {
			$params['orders'] = $this->_orders;
		}
		if($this->_group) {
			$params['group'] = $this->_group;
		}
		if($this->_offset) {
			$params['offset'] = $this->_offset;	
		}
		if($this->_limit) {
			$params['limit'] = $this->_limit;	
		}

		$this->_params = $params;
		$this->_url = $this->_host . $this->_uri;
		$data = array('params'=>json_encode($this->_params));
		$http = new Curl_Request();
		$return = $http->post($this->_url, $data);
		$code = $http->code();

		if($code != 200) {
			throw new Sphinx_Exception('调用sphinx接口失败');
		}

		$return = json_decode($return, true);

		$this->_result = $return;
		$this->_data = isset($return['data'][0]) ? $return['data'][0] : array();

		if(isset($return['data'][1]) && is_array($return['data'][1])) {
			foreach($return['data'][1] as $meta) {
				if($meta['Variable_name'] == 'total_found') {
					$this->_totalFound = $meta['Value'];
				}
				if($meta['Variable_name'] == 'total') {
					$this->_total = $meta['Value'];
				}
				if($meta['Variable_name'] == 'time') {
					$this->_time = $meta['Value'];
				}
			}
		}
		return $this;
	}

	/**
	 * 获取结果
	 * @return mixed
	 */
	public function getResult() {
		return $this->_result;
	}
	
	public function getUrl() {
		return $this->_url;
	}

	public function getParams() {
		return $this->_params;
	}
	
	public function getData() {
		return $this->_data;
	}
	
	public function getTotalFound() {
		return $this->_totalFound;
	}
	
	public function getTotal() {
		return $this->_total;
	}
	
	public function getTime() {
		return $this->_time;
	}
}
