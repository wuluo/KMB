<?php
abstract class Slice_Table {
	
	const MODE_NONE = 0;
	const MODE_TIME_MONTH = 1;
	const MODE_TIME_YEAR = 2;
	const MODE_MOD_64 = 3;
	const MODE_MOD_128 = 4;
	const MODE_MOD_256 = 5;
	const MODE_MOD_512 = 6;
	const MODE_HASH_MD5 = 7;
	
	protected $_name = '';
	
	protected $_key = NULL;
	
	static public function factory($mode) {
		if($mode == Slice_Table::MODE_MOD_64) {
			return new Slice_Table_Mod_64();
		}
		if($mode == Slice_Table::MODE_MOD_128) {
			return new Slice_Table_Mod_128();
		}
		if($mode == Slice_Table::MODE_MOD_256) {
			return new Slice_Table_Mod_256();
		}
		if($mode == Slice_Table::MODE_MOD_512) {
			return new Slice_Table_Mod_512();
		}
		if($mode == Slice_Table::MODE_HASH_MD5) {
			return new Slice_Table_Hash_Md5();
		}
		if($mode == Slice_Table::MODE_TIME_YEAR) {
			return new Slice_Table_Time_Year();
		}
		if($mode == Slice_Table::MODE_TIME_MONTH) {
			return new Slice_Table_Time_Month();
		}
		return new Slice_Table_None();
	}
	
	public function name($name) {
		$this->_name = $name;
		return $this;
	}
	
	public function key($key) {
		$this->_key = $key;
		return $this;
	}
	
	abstract public function route();
}