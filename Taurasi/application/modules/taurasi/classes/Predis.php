<?php
/**
 * Created by PhpStorm.
 * User: zhangyongchao
 * Date: 2017/2/20
 * Time: 15:00
 */
class Predis extends Redisd
{
    public static function instance($type, $force = false) {
        if (self::$_instanceSelf === NULL || $force) {
            self::$_instanceSelf = new Predis($type, $force);
        }
        return self::$_instanceSelf;
    }

    public function zAdd($key, $argments) {
        array_unshift($argments, $key);
        return call_user_func_array([self::$_instance, 'zadd'], $argments);
    }

    public function zRangeByScore($key, $start, $end, $options = [])
    {
        return self::$_instance->zRangeByScore( $key, $start, $end, $options);
    }

    public function zRevRangeByScore($key, $start, $end, $options = [])
    {
        return self::$_instance->zRevRangeByScore( $key, $start, $end, $options);
    }

    public function zRemRangeByScore($key, $start, $end)
    {
        return self::$_instance->zRemRangeByScore($key, $start, $end);
    }

    public function exists($key)
    {
        return self::$_instance->exists($key);
    }

    public function hMGet($key, $argments)
    {
        if (!is_array($argments) || empty($argments)){
            return false;
        }
        return self::$_instance->hMGet($key, $argments);
    }

    public function hMSet($key, $argments)
    {
        if (!is_array($argments) || empty($argments)){
            return false;
        }
        return self::$_instance->hMset($key, $argments);
    }

    public function set($key, $value, $expire = 0)
    {
        if ($expire) {
            return self::$_instance->setex($key, $expire, $value);
        } else {
            return self::$_instance->set($key, $value);
        }
    }

    public function get($key)
    {
        return self::$_instance->get($key);
    }

    public function incr($key){
        return self::$_instance->incr($key);
    }

    public function decr($key){
        return self::$_instance->decr($key);
    }

    public function rPop($key){
        return self::$_instance->rPop($key);
    }

    public function zRevRange($key, $start, $end)
    {
        return self::$_instance->zRevRange($key, $start, $end);
    }

    public function mset($params = [])
    {
        return self::$_instance->mset($params);
    }
}