<?php

namespace KielPack\PropertyLease;

use Illuminate\Support\ServiceProvider;
use KielPack\PropertyLease\App\Http\Routes\PropertyLeaseRoute;

class PropertyLeaseProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ .'/config' => config_path('report-config')

        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ .'/config/main.php','report-config');
        $this->app->bind('propertyLeaseRoute', 'KielPack\PropertyLease\App\Http\Routes\PropertyLeaseRoute');

    }
}
