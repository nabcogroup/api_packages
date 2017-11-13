<?php


namespace Sunriseco\Accounts;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AccountsModuleProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);

        
    }

    public function register() {

        $this->app->bind('accountsRoute','Sunriseco\Accounts\App\Http\Routes\AccountsRoute');
        $loader = AliasLoader::getInstance();
        $loader->alias('AccountsRoutes',"Sunriseco\Accounts\App\Http\Routes\Facades\AccountsRoute");
    }

}