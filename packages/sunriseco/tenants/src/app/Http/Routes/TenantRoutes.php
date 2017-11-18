<?php


namespace Sunriseco\Tenants\App\Http\Routes;

use Illuminate\Support\Facades\Route;

class TenantRoutes
{
    public static function routes() {
        
        Route::group(['prefix' => 'api/tenant'],
            function() {
                Route::post("/store",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@store","roles" => ["contract", "account"]]);
                Route::patch("/edit",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@store","roles" => ["contract", "account"]]);
                
                Route::get("/list",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@all","roles" => ["contract", "account"]]);
                Route::get("/edit/{tenantId}",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@edit","roles" => ["contract", "account"]]);
                Route::get("/search/{regId?}",["uses" =>  "\Sunriseco\Tenants\App\Http\Controllers\TenantController@search","roles" => ["contract", "account"]]);
                
            }
        );


    }
}