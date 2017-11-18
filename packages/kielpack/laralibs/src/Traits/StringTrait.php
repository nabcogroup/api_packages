<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:27 PM
 */

namespace KielPack\LaraLibs\Traits;


trait StringTrait
{
    public function formatUnderscore($value) {

        $underscore_pos = strpos("$value","_");
        if($underscore_pos) {
            $values = explode("_",$value);
            return ucfirst($values[0]) . " " . ucfirst($values[1]);
        }

        return $value;
    }

    public function cleanupAttributes($key,&$attributes) {
        unset($attributes[$key]);
    }


}