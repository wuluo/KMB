<?php

class Image_SinaService {

	public function __construct($service_name='') {

	}

	public function __destruct() {
		return;
	}

}

/*
 * SinaServiceException
 *
 * @author: quyang1@ shangbin@
 * @date: 2011-04-27
 */
class Image_SinaServiceException extends Exception {
	/*
	 * Linefeed
	 * 
	 * @var string 
	 */
        private $linefeed = NULL;
	
	/*
	 * Consturctor
	 * 
	 * @param string $message Exception message
	 * @param integer $code Exception code
	 */
        public function __construct($message = null, $code = 0) {
                parent::__construct($message, $code);
                $this->linefeed = php_sapi_name() == 'cli' ? "\n" : "<br>";
        }
	/*
	 * Get a general message of exception. 
	 * 
	 * @return string
	 */
        public function errorMessage() {
                return sprintf('Error on line %d in %s:%s%s%s', $this->getLine(), $this->getFile(), $this->linefeed, $this->getMessage(), $this->linefeed);
        }
}

?>
