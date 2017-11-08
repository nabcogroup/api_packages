<?php

namespace KielPack\LaraLibs\Selections;

use Illuminate\Database\Eloquent\Model;
use KielPack\LaraLibs\Supports\Memoized;

class SelectionModel extends Model
{
    protected $table = "selections";

    protected $fillable = ["code","name","category","sort_order"];

    protected $hidden = ["created_at","updated_at"];
    //
    public static function getSelections(Array $categories = array()) {

        $categories = static::wherein('category',$categories)->orderBy('sort_order')->get();

        $lookups = array();

        foreach($categories as $key => $value) {
            $lookups[$value['category']][] = $value;
        }
        return $lookups;
    }

    public static function getValue($category,$key) {
        //add value to memoization
        if(!Memoized::isExist($key)) {
            $values = static::where('category',$category)->where('code',$key)->orderBy('category')->get();
            $retValue = "";
            foreach($values as $value) {
                $retValue = $value->name;
            }
            Memoized::addValues($key,$retValue);
        }

        return Memoized::getValues($key);
    }

    public static function getValues(Array $keys = array()) {

        $values = static::wherein('code',$keys)->get();
        return $values;
    }

    public static function convertCode($code) {

        $code = str_replace("_"," ",$code);
        return ucwords($code);

    }
}
