<?php

namespace Sunriseco\Contracts;


use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ContractModuleProvider extends ServiceProvider
{
    public function boot() {

        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);

    }

    public function register() {

        $this->app->bind('contractRoutes',"Sunriseco\Contracts\App\Http\Routes\ContractRoutes");

        $loader = AliasLoader::getInstance();

        $loader->alias('ContractRoutes',"Sunriseco\Tenants\App\Http\Routes\Facades\ContractRoutes");

    }


}