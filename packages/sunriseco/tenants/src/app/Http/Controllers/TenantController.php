<?php 


namespace Sunriseco\Tenants\App\Http\Controllers;

use Sunriseco\Tenants\App\Models\Tenant;
use KielPack\LaraLibs\Supports\Facades\Result;

use Sunriseco\Tenants\App\Http\Requests\TenantForm;
use Sunriseco\Tenants\App\Repositories\TenantRepository;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;

class TenantController {

    private $repo;
    
        public function __construct(TenantRepository $repo)
        {
            $this->repo = $repo;
        }

        //[Get: http://../api/all]
        public function all() {
            
            $tenants = $this->repo->getTenants();
            
            return $tenants;
        }
    
        //[Get: http://../api/create]
        public function create() {
    
            $tenant = Tenant::createInstance();
            $lookups = Selection::getSelections(["tenant_type"]);

            return compact('tenant','lookups');

        }
    
        //[Get: http://../api/show]
        public function show($tenantId) {
    
            $tenant = $this->repo->includes(['address'])->findById($tenantId)->single();
            $lookups = Selection::getSelections(['tenant_type']);
    
            return compact('tenant','lookups');

        }
    

        //[Post: http://../api/store]
        public function store(TenantForm $request) {
            
            $inputs = $request->filterInput();
            
            try {
                $this->repo->saveTenant($inputs);
            }
            catch(Exception $e) {
                Result::badRequest(["message" => $e->getMessage()]);
            }
        }
    
        //[Get: http://../api/search]
        public function search($regId = "") {
            try {
                $tenant = $this->repo->getTenantByRegId($regId);
                if($tenant == null) {
                    throw new Exception("Cannot Find Tenant");
                }
            }
            catch(Exception $e) {
                
                return Result::badRequest(["message" => $e->getMessage()]);

            }
            
            return $tenant;
        }


}