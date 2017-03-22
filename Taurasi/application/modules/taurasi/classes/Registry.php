<?php

/**
 * Registry class
 * 对象注册表(全局容器存储)[get|has|set|del]
 * @author guoquan-ds@gomeplus.com
 */
final class Registry
{
    private static $_entries;

    private function __construct() {}

    private function __clone() {}

    public static function get(string $name)
    {
        return self::has($name) ? self::$_entries[$name] : null;
    }

    public static function has(string $name)
    {
        return array_key_exists($name, self::$_entries);
    }

    public static function set(string $name, $value)
    {
        self::$_entries[$name] = $value;
    }

    public static function del(string $name)
    {
        if (self::has($name)) {
            unset(self::$_entries[$name]);
        }
    }
}
