<?php

/**
 * Class Exception_Exception
 * @author guoquan
 */
class Exception_Exception extends Kohana_Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null, array $variables = null)
    {
        parent::__construct($message, $variables, $code, $previous);
    }
}
