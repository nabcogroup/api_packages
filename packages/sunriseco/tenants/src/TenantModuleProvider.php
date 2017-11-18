<?php

namespace Sunriseco\Tenants;

use Illuminate\Support\ServiceProvider;

class TenantModuleProvider extends ServiceProvider
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
        $loader->alias('TenantRoutes',"Sunriseco\Properties\App\Http\Routes\Facades\TenantRoutes");

    }
}
