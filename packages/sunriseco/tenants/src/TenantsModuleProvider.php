<?php

namespace Sunriseco\Tenants;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class TenantsModuleProvider extends ServiceProvider
{

    public function boot()
    {   
        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);
    }

    public function register() {
        
        $this->app->bind('tenantRoutes',"Sunriseco\Tenants\App\Http\Routes\TenantRoutes");
        
        $loader = AliasLoader::getInstance();
        $loader->alias('TenantRoutes',"Sunriseco\Tenants\App\Http\Routes\Facades\TenantRoutes");

    }
}
