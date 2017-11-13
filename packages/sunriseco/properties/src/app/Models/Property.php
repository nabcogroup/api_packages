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

    protected $appends = ['total_units'];

    /**navigation**/
    public function villas() {
        return $this->hasMany(Villa::class,"property_id","id");
    }


    /**mutator accessor**/
    public function getTotalUnitsAttribute() {
        return $this->villas()->count();
    }

}