<?php

namespace KielPack\LaraLibs;

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
    }
}
