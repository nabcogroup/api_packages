<?php

namespace KielPack\LaraLibs\Providers;

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
