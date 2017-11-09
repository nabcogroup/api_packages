<?php

namespace KielPack\PropertyLease\App\Http\Routes;


use Illuminate\Support\Facades\Route;

class PropertyLeaseRoute
{
    public static function fixedAssetRoute() {

        Route::group(['prefix' => 'api/fixed-asset/'],
            function() {
                Route::post("/store", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@store");
                Route::get("create", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@create");
                Route::get("show/{id}", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@show");
                Route::get("{property?}", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@all");
            }
        );
    }



}