<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.08.2016
 * Time: 10:05
 */
class Config{
    protected static $settings = array();

    public static function get($key){
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    public static function set($key, $value){
        self::$settings[$key] = $value;
    }
}