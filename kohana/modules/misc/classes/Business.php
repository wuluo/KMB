<?php
abstract class Business {
	
	static public function factory($name) {
		$class = 'Business_'. ucfirst($name);
		
		return new $class;
	}
	
} 
