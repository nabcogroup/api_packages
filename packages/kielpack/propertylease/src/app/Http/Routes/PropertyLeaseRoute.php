<?php

namespace KielPack\PropertyLease\App\Http\Routes;


use Illuminate\Support\Facades\Route;

class PropertyLeaseRoute
{
    public static function route() {

        Route::group(['prefix' => 'api/fixed-asset/'],
            function() {

                Route::post("/store", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@store");
                Route::patch("/", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@store");
                Route::get("create", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@create");
                Route::get("edit/{id}", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@edit");
                Route::get("{property?}", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@all");
            }
        );
    }
}