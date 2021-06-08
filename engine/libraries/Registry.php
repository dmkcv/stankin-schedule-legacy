<?php

/**
 * Project: FlyprintN
 * Date: 30.01.2017
 * Time: 19:56
 */

class Registry
{
    static protected $data;
    static protected $lock = array();
    public static function set($key, $value) {
        if ( !self::hasLock($key) ) {
            self::$data[$key] = $value;
        } else {
            throw new RuntimeException("переменная '$key' заблокирована для изменений");
        }
    }
    public static function get($key, $default = null) {
        if ( self::has($key) ) {
            return self::$data[$key];
        } else {
            return $default;
        }
    }
    public static function remove($key) {
        if ( self::has($key) && self::hasLock($key) ) {
            unset(self::$data[$key]);
        }
    }
    public static function has($key) {
        return isset(self::$data[$key]);
    }
    public static function lock($key) {
        self::$lock[$key] = true;
    }
    public static function hasLock($key) {
        return isset(self::$lock[$key]);
    }
    public static function unlock($key) {
        if ( self::hasLock($key) ) {
            unset(self::$lock[$key]);
        }
    }
}