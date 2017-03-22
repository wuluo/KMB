<?php
/**
 * 记录异常日志
 * @author 
 */

class Log_Database extends Log_Writer {

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
	
	public function __construct() {
		$this->_table = Kohana::$config->load("logger_crash.custom.table");
		$this->_connection = Kohana::$config->load("logger_crash.custom.connection");
		$this->_charset = Kohana::$config->load("logger_crash.custom.charset");
	}

	public function write(array $messages) {
		$this->_connect();
		foreach ($messages as $message) {
			$this->_write($message);
		}
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
	
	protected function _write(array $message) {

		$values = array (
			'level' => $message['level'],
			'ip' => isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : '',
			'hostname' => isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : '',
			'file' => $message['file'],
			'line' => $message['line'],
			'message' => $message['body'],
			'create_time' => $message['time'],
		);

		if(isset($message['additional']['exception'])) {
			if($values['file'] === NULL) {
				$values['file'] = $message['additional']['exception']->getFile();
			}
			if($values['line'] === NULL) {
				$values['line'] = $message['additional']['exception']->getLine();
			}
		}

		$columns = array_keys($values);

		$columns = "`". implode("`,`", $columns) ."`";
		$values = "'". implode("','", $values) ."'";
		
		$query = "INSERT INTO {$this->_table} ($columns) VALUES ($values)";
		$result = self::$_DB->exec($query);

		return array(self::$_DB->lastInsertId(), 1);
	}
}
