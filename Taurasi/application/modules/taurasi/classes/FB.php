<?php

/**
 * Class FB
 *
 * @author guoquan-ds <guoquan-ds@gomeplus.com>
 */
class FB
{
    protected        $_firephp;
    protected static $_instance;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        require Kohana::find_file('vendor', 'FirePHP.class');
        $this->_firephp = FirePHP::getInstance(true);
    }

    public function send()
    {
        $args = func_get_args();
        return call_user_func_array([$this->_firephp, 'fb'], $args);
    }

    public function log($object, $label = null, $options = [])
    {
        $this->_firephp->fb($object, $label, FirePHP::LOG, $options);
        return $this;
    }

    public function info($object, $label = null, $options = [])
    {
        $this->_firephp->fb($object, $label, FirePHP::INFO, $options);
        return $this;
    }

    public function warn($object, $label = null, $options = [])
    {
        $this->_firephp->fb($object, $label, FirePHP::WARN, $options);
        return $this;
    }

    public function error($object, $label = null, $options = [])
    {
        $this->_firephp->fb($object, $label, FirePHP::ERROR, $options);
        return $this;
    }

    public function dump($key, $variable)
    {
        $this->send($variable, $key, FirePHP::DUMP);
        return $this;
    }

    public function table($label, $table)
    {
        $this->send($table, $label, FirePHP::TABLE);
        return $this;
    }

    public function trace($label)
    {
        return $this->send($label, FirePHP::TRACE);
    }

    public function group($name, $options = null)
    {
        $this->_firephp->group($name, $options);
        return $this;
    }

    public function groupEnd()
    {
        $this->send(null, null, FirePHP::GROUP_END);
        return $this;
    }
}
