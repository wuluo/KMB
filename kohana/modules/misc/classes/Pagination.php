<?php
/**
 * 分页类
 * @author dongjie
 */
class Pagination {

	/**
	 * 分页变量
	 * @var string
	 */
	protected $_key = 'page';
	
	/**
	 * 查询参数
	 * @var array
	 */
	protected $_queries = array();
	
	/**
	 * 当前页
	 * @var number
	 */
	protected $_current = 0;
	
	/**
	 * 第一页
	 * @var number
	 */
	protected $_first = 1;
	
	/**
	 * 最后一页
	 * @var number;
	 */
	protected $_last = 1;
	
	/**
	 * 下一页
	 * @var number
	 */
	protected $_next = 0;
	
	/**
	 * 前一页
	 * @var number
	 */
	protected $_previous = 0;

	/**
	 * 最大页
	 * @var number
	 */
	protected $_max = 1;
	
	/**
	 * 每页记录数
	 * @var number
	 */
	protected $_number = 20;
	
	/**
	 * 偏移
	 * @var number
	 */
	protected $_offset = 0;

	/**
	 * 总记录数
	 * @var number
	 */
	protected $_total = 1;
	
	/**
	 * 模板
	 * @var string
	 */
	protected $_template = 'pagination/default';

	
	static public function factory($total = 0, $number = 20, $key = 'page') {
		return new self($total, $number, $key);
	}
	
	/**
	 * 构造
	 */
	public function __construct($total = 0, $number = 20, $key = 'page') {
		$this->total($total);
		$this->number($number);
		$this->key($key);
	}
	
	/**
	 * 分页KEY
	 * @param string $key
	 * @return Pagination
	 */
	public function key($key = 'page') {
		$this->_key = $key;
		return $this;
	}
	
	/**
	 * setter query item
	 * @param string $key
	 * @param string $value
	 * @return Pagination
	 */
	public function set($key, $value) {
		$this->_queries[$key] = $value;
		return $this;
	}
	
	/**
	 * 每页记录数
	 * @param number $number
	 * @return Pagination
	 */
	public function number($number = 20) {
		$this->_number = $number;
		return $this;
	}
	
	/**
	 * 总数
	 * @param number $total
	 * @return Pagination
	 */
	public function total($total = 0) {
		$this->_total = $total;
		return $this;
	}

	/**
	 * 模板
	 * 
	 * @param string $template
	 * @return Pagination
	 */
	public function template($template) {
		$this->_template = $template;
		return $this;
	}
	
	/**
	 * 返回分页变量
	 * @return string
	 */
	public function getKey() {
		return $this->_key;
	}
	
	/**
	 * 获取自定义get参数
	 * @return multitype:
	 */
	public function getQueries() {
		return $this->_queries;
	}

	/**
	 * 执行
	 */
	public function execute() {
		$current = Arr::get($_GET, $this->_key, 1);
		$this->_current = is_numeric($current) && $current > 0 ? $current : 1;

		$this->_max = ceil($this->_total / $this->_number);
		$this->_max = $this->_max > 0 ? $this->_max : 1;
		$this->_current = min(max($this->_current, 1), min($this->_current, $this->_max));
		$this->_next = $this->_current >= $this->_max ? NULL : $this->_current + 1;
		$this->_previous = $this->_current <= 1 ? NULL : $this->_current - 1;
		$this->_first = $this->_current === 1 ? NULL : 1;
		$this->_last = $this->_current === $this->_max ? NULL : $this->_max;
		$this->_offset = ($this->_current - 1) * $this->_number;
		
		return $this;
	}
	
	public function URL($number) {
		if(is_numeric($number)) {
			$queries = array_merge($this->_queries, array($this->_key => $number));
		}
		return URL::site(Request::current()->uri().URL::query($queries));
	}

	public function __toString() {
		return View::factory($this->_template)
			->set('pagination', $this)
			->render();
	}

	public function __get($key) {
		$key = '_'. $key;
		return isset($this->$key) ? $this->$key : NULL;
	}
}
