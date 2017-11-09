<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 10:44 AM
 */

namespace Sunriseco\Properties\App\Models;


use KielPack\LaraLibs\Base\BaseModel;

class Property extends BaseModel
{
    protected $table = "properties";

    protected $fillable = ["code","name","address"];

    /**navigation**/
    public function villa() {
        return $this->hasMany(Villa::class,"property_code","code");
    }


    /**mutator accessor**/


}