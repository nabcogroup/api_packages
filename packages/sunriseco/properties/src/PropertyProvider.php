<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 11:51 AM
 */

namespace Sunriseco\Properties;


use Illuminate\Support\ServiceProvider;

class PropertyProvider extends ServiceProvider
{


    public function boot() {

        require  __DIR__ .'routes.php';
        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);
    }


    public function register(){

    }
}