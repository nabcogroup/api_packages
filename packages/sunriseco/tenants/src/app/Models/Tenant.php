<?php

namespace Sunriseco\Tenants\App\Models;


use KielPack\LaraLibs\Base\BaseModel;

use Illuminate\Database\Eloquent\Model;


use Sunriseco\Contracts\App\Models\Contract as Contract;

class Tenant extends BaseModel
{
    //
    protected $fillable = ['type','full_name','email_address','tel_no','mobile_no','fax_no','reg_date','reg_id','reg_name','gender'];

    public static function createInstance() {

        $tenant = new Tenant();
        $tenant->address = new TenantAddress();
        return $tenant;
    }

    public function __construct(array $attributes = [])
    {
        if(empty($attributes)) {

            $attributes['type'] = 'individual';

            $attributes['reg_date'] = \Carbon\Carbon::now()->toDateTimeString();

            $attributes['gender'] = 'male';
            
        }

        parent::__construct($attributes);
    }








    /*****************
     *  Navigation
     ***************************/
    public function address() {

        return $this->hasOne(TenantAddress::class);

    }


    public function contracts() {
        return $this->hasMany(Contract::class);
    }

    /*****************
     *  mutators
    ***************************/
    public function setFullNameAttribute($value) {

        $this->attributes['full_name'] = ucwords($value);

    }

    public function getFullNameAttribute($value) {

        return ucwords($value);

    }





}
