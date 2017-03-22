<?php
class Logger_Writer_Database extends Logger_Writer {
	
	static protected $_DB = NULL;
	
	protected $_connection = array(
		'hostname' => '127.0.0.1',
		'database' => '',
		'username' => '',
		'password' => '',
		'persistent' => FALSE,
	);
	
	protected $_charset = 'utf8';
	
	protected $_table = '';
	
	protected $_columns = array(
		'portal',
		'controller',
		'action',
		'get',
		'post',
		'message',
		'ip',
		'user_agent',
		'referer',
		'account_id',
		'account_name',
		'create_time',
	);
	
	public function __construct($config) {
		$this->_table = $config['table'];
		$this->_columns = $config['columns'];
		$group = $config['group'];
		$this->_connection = Kohana::$config->load("database.{$group}.connection");
		$this->_charset = Kohana::$config->load("database.{$group}.charset");
	}
	
	public function write($message) {
		return $this->_connect()->_write($message);
	}
	
	protected function _connect() {
		if(self::$_DB !== NULL) {
			return $this;
		}
		if($this->_connection['persistent']) {
			self::$_DB = new PDO($this->_connection['dsn'], $this->_connection['username'], $this->_connection['password'], array(PDO::ATTR_PERSISTENT=>true));
		} else {
			self::$_DB = new PDO($this->_connection['dsn'], $this->_connection['username'], $this->_connection['password'], array(PDO::ATTR_PERSISTENT=>false));
		}
		return $this;
	}
	
	protected function _write($message) {
		$values = array();
		foreach($message as $key => $value) {
			// if(!in_array($key, $this->_columns)) {
			// 	continue;
			// }
			$values[$key] = str_replace("'", '', $value);
		}
		$columns = array_keys($values);
		$columns = "`". implode("`,`", $columns) ."`";
		$values = "'". implode("','", $values) ."'";
		
		$query = "INSERT INTO {$this->_table} ($columns) VALUES ($values)";
		$result = self::$_DB->exec($query);
		if(!$result) {
			throw new Logger_Exception("日志写入失败:". json_encode(self::$_DB->errorInfo()));
		}
		return array(self::$_DB->lastInsertId(), 1);
	}
}
