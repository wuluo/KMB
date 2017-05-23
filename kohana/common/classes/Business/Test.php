<?php
class Business_Test extends Business {
	
	public function test() {
		return Dao::factory('Test')->getList();
	}
	
}