<?php
abstract class Slice_DB {
	
	const MODE_NONE = 0;
	const MODE_TIME_MONTH = 1;
	const MODE_TIME_YEAR = 2;
	const MODE_MOD_2 = 3;
	const MODE_MOD_4 = 4;
	const MODE_MOD_8 = 5;
	const MODE_MOD_16 = 6;
	const MODE_HASH_MD5 = 7;
	
	protected $_name = '';
	
	protected $_key = NULL;
	
	static public function factory($mode) {
		if($mode == Slice_DB::MODE_MOD_2) {
			return new Slice_DB_Mod_2();
		}
		if($mode == Slice_DB::MODE_MOD_4) {
			return new Slice_DB_Mod_4();
		}
		if($mode == Slice_DB::MODE_MOD_8) {
			return new Slice_DB_Mod_8();
		}
		if($mode == Slice_DB::MODE_MOD_16) {
			return new Slice_DB_Mod_16();
		}
		if($mode == Slice_DB::MODE_HASH_MD5) {
			return new Slice_DB_Hash_MD5();
		}
		if($mode == Slice_DB::MODE_TIME_YEAR) {
			return new Slice_DB_Time_Year();
		}
		if($mode == Slice_DB::MODE_TIME_MONTH) {
			return new Slice_DB_Time_Month();
		}
		return new Slice_DB_None();
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