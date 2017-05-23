<?php

/**
 * Conf Class配置文件链式操作
 * @author guoquan <1257669560@qq.com>
 */
final class Conf
{
    protected $container = [];

    private function __construct(array $config = [])
    {
        $this->container = array_map(function ($value) {
            return is_array($value) ? new static($value) : $value; 
        }, $config);
    }
    public static function make($file)
    {
        return new self(Kohana::$config->load($file)->as_array());
    }
    public function has($name)
    {
        return isset($this->container[$name]);
    }
    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->container[$name] : $default;
    }
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof self ? $value->toArray() : $value;
        }, $this->container);
    }
    public function __get($name)
    {
        return $this->get($name);
    }
    public function __isset($name)
    {
        return $this->has($name);
    }
}
