<?php
class Slice_Table_Mod_512 extends Slice_Table {
	
	public function route() {
		return $this->_name.'_'.($this->_key % 512); 
	}
}