<?php


Route::group(['prefix' => 'api/property'],
    function() {
        Route::post("/store", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@store");
        Route::get("create", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@create");
        Route::get("show/{id}", "\KielPack\PropertyLease\App\Http\Controllers\FixedAssetController@show");
        Route::get("/", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@all");

    }
);