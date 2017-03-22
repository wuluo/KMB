<?php
class Slice_Table_Time_Year extends Slice_DB {
	
	public function route() {
		return $this->_name.'_'.date('Y', $this->_key);
	}
}