<?php


namespace Sunriseco\Tenants\App\Repositories;


use Faker\Provider\Address;
use App\Traits\PaginationTrait;
use Sunriseco\Tenants\App\Models\Tenant;
use KielPack\LaraLibs\Traits\StringTrait;
use Sunriseco\Tenants\App\Models\TenantAddress;

class TenantRepository extends AbstractRepository {

    use PaginationTrait,StringTrait;

    protected function definedModel() {
        return new Tenant();
    }

    protected function afterUpdate(&$model,$children = array())
    {
        $parentId = $model->getId();
        $tenantAddressModel = TenantAddress::where('tenant_id',$parentId)->get();
        foreach ($tenantAddressModel as $address) {
            $address->save($children);
        }
    }

    protected function afterCreate(&$model,$children = array()) {
        
        $address = new TenantAddress($children);
        
        $model->TenantAddress()->save($address);

    }

    public function saveTenant($model) {

        $addressInstance = isset($model['tenant_address']) ? $model['tenant_address'] : false;
        
        //remove tenant
        $this->cleanupAttributes('tenant_address',$model);

        $tenant = $this->attach($model,$addressInstance)->instance();
        
        return $tenant;
    }

    public function getTenants($inputs = array()) {

        $params = [];

        $tenants = $this->model->customFilter($params);
        
        return $this->createPagination($tenants,function($row) {
            
            $item = [
                'id'            =>  $row->id,
                'full_name'     =>  $row->full_name,
                'reg_id'        =>  $row->reg_id,
                'email_address' =>  $row->email_address,
                'mobile_no'     =>  $row->mobile_no,
            ];

            return $item;

        },$params);


    }

    public function getTenantByRegId($regId) {

        return $this->model->with('TenantAddress')->where('reg_id',$regId)->first();
        
    }

    public function withChildren() {

        $this->model = $this->model->with('TenantAddress');


    }


    
}