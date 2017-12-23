<?php

namespace KielPack\LaraLibs;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class LaraLibProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . "/Selections/migrations" => base_path("database/migrations")
        ]);

        $this->loadFunction();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {


        $this->app->bind('bundle','KielPack\LaraLibs\Supports\Bundle');
        $this->app->bind('result','KielPack\LaraLibs\Supports\Result');
        $this->app->bind('fileManager','KielPack\LaraLibs\Supports\FileManager');
        $this->app->bind('eventListenerRegister','KielPack\LaraLibs\Supports\EventListenerRegister');
        
        //auto register
        $loader = AliasLoader::getInstance();
        $loader->alias('Bundle','KielPack\LaraLibs\Supports\Facades\Bundle');
        $loader->alias('EventListenerRegister','KielPack\LaraLibs\Supports\Facades\EventListenerRegister');
        $loader->alias('FileManager','KielPack\LaraLibs\Supports\Facades\FileManager');
        $loader->alias('Result','KielPack\LaraLibs\Supports\Facades\Result');
    }


    public function loadFunction() {

        $helper = __DIR__ . "/Helpers/functions.php";

        if(file_exists($helper)) {
            include_once ($helper);
        }
    }
}
