<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:23 PM
 */

namespace KielPack\LaraLibs\Traits;


trait SerializeTrait
{
    public function setMetaValue(&$meta,$values,$unserializeable = false) {
        if($unserializeable) {
            $meta = $values;
        }
        else {
            $meta = serialize($values);
        }
    }

    public function getMetaValue(&$value,$unserializeable = false) {

        if(!empty($value)) {
            if($unserializeable) {
                $meta_galleries = [$value];
            }
            else {
                $meta_galleries = unserialize($value);
            }
            return $meta_galleries;
        }
        return null;
    }

}