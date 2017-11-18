<?php


namespace Sunriseco\Tenants\App\Http\Routes;

class TenantRoutes
{
    public static function routes() {
        
        Route::group(['prefix' => 'api/tenant'],
            function() {
                Route::get("/list",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@apiList","roles" => ["contract", "account"]]);
                Route::get("/edit/{tenantId}",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@apiShow","roles" => ["contract", "account"]]);
                Route::get("/search/{regId?}",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@apiSearch","roles" => ["contract", "account"]]);
                Route::post("/store",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@apiStore","roles" => ["contract", "account"]]);
            }
        );


    }
}