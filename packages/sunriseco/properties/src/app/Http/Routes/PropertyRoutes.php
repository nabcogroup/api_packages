<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 5:24 PM
 */

namespace Sunriseco\Properties\App\Http\Routes;


use Illuminate\Support\Facades\Route;

class PropertyRoutes
{
    public static function routes() {

        Route::group(['prefix' => 'api/property'],

            function() {

                Route::post("/store", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@store");

                Route::patch("/update", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@store");

                Route::get("/{id}/edit", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@edit");

                Route::get("/create", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@create");

                Route::get("", "\Sunriseco\Properties\App\Http\Controllers\PropertyController@all");

                Route::get("{id}/villa/create/", "\Sunriseco\Properties\App\Http\Controllers\UnitController@create");

            }
        );


    }
}