<?php
abstract class Dao {
	
	/**
	 * 数据库配置名
	 * @var string
	 */
	protected $_db = NULL;
	
	/**
	 * 表名
	 * @var string
	 */
	protected $_tableName = '';
	
	/**
	 * 主键
	 * @var string
	 */
	protected $_primaryKey = '';
	
	/**
	 * 库路由
	 * @var integer
	 */
	protected $_routeDB = Slice_DB::MODE_NONE;
	
	/**
	 * 表路由
	 * @var integer
	 */
	protected $_routeTable = Slice_Table::MODE_NONE;
	
	
	/**
	 * Create a new dao instance.
	 *
	 *     $dao = Dao::factory($name);
	 *
	 * @param   string  $name   dao name
	 * @return  Dao
	 */
	public static function factory($name, $db = NULL) {
		// Add the dao prefix
		$class = 'Dao_'.$name;

		return new $class($db);
	}

	public function __construct($db = NULL) {
		if($db) {
			$this->_db = $db;
		}
		
		if(is_string($this->_db)) {
			$this->_db = Database::instance($this->_db);
		}
	}

	protected function _db($key) {
		if(!$this->_routeDB instanceof Slice_DB) {
			$this->_routeDB = Slice_DB::factory($this->_routeDB);
		}
		return $this->_routeDB->name($this->_db)->key($key)->route();
	}
	
	protected function _tableName($key) {
		if(!$this->_routeTable instanceof Slice_Table) {
			$this->_routeTable = Slice_Table::factory($this->_routeTable);
		}
		return $this->_routeTable->name($this->_tableName)->key($key)->route();
	}
	
	protected function _groupkeys(array $keys) {
		$keysGroups = array();
		foreach($keys as $key) {
			$groupKey = $this->_db($key).'.'.$this->_tableName($key);
			$keysGroups[$groupKey][] = $key;
		}
		return $keysGroups;
	}
}
