<?php
class Slice_DB_Hash_Md5 extends Slice_DB {
	
	public function route() {
		return $this->_name.'_'.substr(md5($this->_key), -1);
	}
}