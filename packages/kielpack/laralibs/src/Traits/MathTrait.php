<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/8/2017
 * Time: 9:53 AM
 */

namespace KielPack\LaraLibs\Traits;


trait MathTrait
{
    public function breakApart($value1,$value2) {
        $result = 0;
        if($value1 > 0) {
            $result = $value1 / $value2;
        }
        return $result;
    }

    public function sum($items,$field) {
        $total = 0;
        if(is_array($items)) {
            foreach($items as $item) {
                if(is_object($item) && is_numeric($item->{$field})) {
                    $total += floatval($item->{$field});
                }
                else if(is_numeric($item[$field])) {
                    $total += floatval($item[$field]);
                }
            }
        }
        return $total;
    }
}