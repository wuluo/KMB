<?php
abstract class Model {
	
	public static function factory($name) {
		$class = 'Model_' . ucfirst($name);
		return new $class;
	}
	
	private $_properties = array();
	
	public function __construct($data = array()) {
		if($data) {
			$this->_properties = $data;
		}
	}
	
	public function __set($item, $value) {
		if(is_string($item)) {
			$this->_properties[$item] = $value;
		}
		return $this;
	}
	
	public function __get($item) {
		return isset($this->_properties[$item]) ? $this->_properties[$item] : NULL;
	}
	
	public function __call($method, $arguments) {
		if(substr($method, 0, 3) == 'get') {
			$method = substr($method, 3, strlen($method));
			$key = implode('_', preg_split('#(?=[A-Z])#', lcfirst($method)));
			$key = strtolower($key);
			return isset($this->_properties[$key]) ? $this->_properties[$key] : NULL;
		}
		return NULL;
	}
	
	public function setArray(array $values = array()) {
		$this->_properties = $values;
		return $this;
	}
	
	public function asArray() {
		return $this->_properties;
	}
}