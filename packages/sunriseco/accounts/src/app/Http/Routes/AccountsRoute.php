<?php

namespace Sunriseco\Accounts\App\Http\Routes;


use Illuminate\Support\Facades\Route;

class AccountsRoute
{
    public static function routes() {

        Route::group(['prefix' => 'api/fixed-asset/'],
            
            function() {

                Route::post("store", "\Sunriseco\Accounts\App\Http\Controllers\FixedAssetController@store");
                Route::get("create", "\Sunriseco\Accounts\App\Http\Controllers\FixedAssetController@create");
                Route::get("show/{id}", "\Sunriseco\Accounts\App\Http\Controllers\FixedAssetController@show");
                Route::get("{property?}", "\Sunriseco\Accounts\App\Http\Controllers\FixedAssetController@all");
                
            }
        );
    }



}