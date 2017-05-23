<?php

abstract class Model_BaseModel {

	public function __call($method, $arguments) {
		if (substr($method, 0, 3) == 'get') {
			
			$method = substr($method, 3, strlen($method));
			$key = implode('_', preg_split('#(?=[A-Z])#', lcfirst($method)));
			$key = strtolower($key);
			return isset($this->$key) ? $this->$key : NULL;
		}
		return NULL;
	}

}
