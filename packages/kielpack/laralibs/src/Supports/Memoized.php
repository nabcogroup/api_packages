<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:11 PM
 */

namespace KielPack\LaraLibs\Supports;


class Memoized
{

    protected static $memoizations = [];

    public static function addValues($key,$values) {
        if(!isset(self::$memoizations[$key])) {
            self::$memoizations[$key] = $values;
        }
    }

    public static function getValues($key) {
        return (isset(self::$memoizations[$key])) ? self::$memoizations[$key] : false;
    }

    public static function isExist($key) {
        return (isset(self::$memoizations[$key])) ? true : false;
    }
}