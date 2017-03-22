<?php
abstract class Logger_Writer {
	
	static public function factory($type) {
		if($type == 'database') {
			$config = Kohana::$config->load('logger.database');
			return new Logger_Writer_Database($config);
		}
		throw new Logger_Exception('No Logger_Writer type: '.$type);
	}
	
	abstract public function write($message);
	
}