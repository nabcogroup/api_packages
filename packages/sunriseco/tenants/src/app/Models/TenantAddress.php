<?php

namespace Sunriseco\Tenants\App\Models;

use KielPack\LaraLibs\Base\BaseModel;

class TenantAddress extends BaseModel
{

    public $timestamps = false;

    protected $fillable = ['address_1','address_2','city','postal_code'];

    protected $appends = ['full_address'];
    
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }



    public function getFullAddressAttribute() {

        return $this->address_1 . " " . $this->address_2 . " " . $this->city . " " . $this->postal_code;
    }
}
