<?php
class Slice_Table_Hash_Md5 extends Slice_Table {
	
	public function route() {
		return $this->_name.'_'.substr(md5($this->_key), -2);
	}
}