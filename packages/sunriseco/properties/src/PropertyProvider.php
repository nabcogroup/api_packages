<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 11:51 AM
 */

namespace Sunriseco\Properties;



use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Sunriseco\Properties\App\Routes\PropertyRoutes;

class PropertyProvider extends ServiceProvider
{


    public function boot() {

        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);

    }


    public function register(){

        $this->app->bind('propertyRoutes',"Sunriseco\Properties\App\Http\Routes\PropertyRoutes");

        $loader = AliasLoader::getInstance();

        $loader->alias('PropertyRoutes',"Sunriseco\Properties\App\Http\Routes\Facades\PropertyRoutes");

    }
}