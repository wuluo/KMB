<?php
/**
 * Cas登录数据解析类
 * @author baishen
 */
class Author_Cas {

	private $_employeeId = 0;
	private $_name = '';
	private $_givenName = '';
	private $_email = '';
	private $_phone = '';
	private $_departments = '';

	private static $_instance = NULL;

	public static function instance() {
		self::$_instance = new self();
		return self::$_instance;
	}

	public function xmlString($xmlString) {
		$this->_xmlString = $xmlString;
		return $this;
	}

	public function execute() {

		$this->_employeeId = $this->_getValue('username');
		$this->_name = $this->_getValue('email');
		$this->_givenName = $this->_getValue('erpname');
		$this->_email = $this->_getValue('fullemail');
		$this->_phone = $this->_getValue('telephone');
		$organization = $this->_getValue('organization');
		$organizationt3 = $this->_getValue('organizationt3');

		if(empty($organizationt3) || $organizationt3 == 'null') {
			$organizationt3 = '';
		}
		if(empty($organization) || $organization == 'null') {
			$organization = '';
		}

		$departments = $organizationt3.' '.$organization;
		$delimiter = array('_', '-');
		$departments = str_replace($delimiter, ' ', $departments);
		$departments = trim($departments);

		$this->_departments = $departments;

		return $this;
	}

	public function getEmployeeId() {
		return $this->_employeeId;
	}

	public function getName() {
		return $this->_name;
	}

	public function getGivenName() {
		return $this->_givenName;
	}

	public function getEmail() {
		return $this->_email;
	}

	public function getPhone() {
		return $this->_phone;
	}

	public function getDepartments() {
		return $this->_departments;
	}

	private function _getValue($name) {
		$value = '';
		$xmlString = $this->_xmlString;
		$i1 = stripos($xmlString, "<$name>");
		$i2 = stripos($xmlString, "</$name>");
		if ($i1 >= 0 && $i2 > 1) {
			$i1 = $i1 + strlen($name) + 2;
			$value = substr($xmlString, $i1, $i2 - $i1);
		}
		return $value;
	}
}
