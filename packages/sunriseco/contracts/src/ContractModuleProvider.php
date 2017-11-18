<?php

namespace Sunriseco\Contracts;


use Illuminate\Support\ServiceProvider;

class ContractModuleProvider extends ServiceProvider
{
    public function boot() {
        $this->publishes([
            __DIR__ . "/database/migrations" => base_path("database/migrations")
        ]);
    }

    public function register() {

    }
}