<?php
class Slice_DB_Mod_4 extends Slice_DB {
	
	public function route() {
		return $this->_name.'_'.($this->_key % 4); 
	}
}