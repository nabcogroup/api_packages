<?php

namespace KielPack\LaraLibs\Traits;

trait ArrayGroupTrait
{
    
    public function arrayItemize(&$collection, $callback, $group = array())
    {
        $items = [];
        $array = $collection->get();
        foreach ($array as $key => $row) {
            $item = $callback($row);
            if (!empty($group)) {
                foreach ($group as $key => $value) {
                    if (!isset($items[$row->$value])) {
                        $items[$row->$value] = [$item];
                    } else {
                        array_push($items[$row->$value], $item);
                    }
                }
            } else {
                array_push($items, $item);
            }
        }

        return $items;
    }

    public function arrayGroup(&$collection, $callback = null, $group = array(), $sortBy = '') {
        $items = [];
        foreach ($collection as $key => $row) {
            if ($callback == null) {
                $item = $row;
            } else {
                $item = $callback($row);
            }
            if (!empty($group)) {
                foreach ($group as $key => $value) {
                    if (!isset($items[$row->$value])) {
                        $items[$row->$value] = [$item];
                    } else {
                        array_push($items[$row->$value], $item);
                    }
                }
            } else {
                array_push($items, $item);
            }
        }


        return [
            'data' => $items
        ];
    }


    public function arrayGroupBy($collection,$callback = null,$keys = array())
    {
        $_key = $keys[0];
        
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($collection as $value) {
            $key = null;
            if (is_object($value) && isset($value->{$_key})) {
                $key = $value->{$_key};
            } 
            elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }
            else {
                continue;
            }

            if($callback === null) {
                $grouped[$key][] = $value;
            }
            else {
                $grouped[$key][] = $callback($value);
            }
            
        }

        
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (sizeof($keys) > 1) {
            $args = func_get_args();
            //dd($args);
            foreach ($grouped as $key => $value) {
                $ret_keys = array_slice($keys,1,sizeof($keys));
                $params = array_merge([$value,$callback],[$ret_keys]);
                $grouped[$key] = call_user_func_array(array($this,'arrayGroupBy'), $params);
            }
        }
        return $grouped;
    }
}