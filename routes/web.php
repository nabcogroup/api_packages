<?php


use Sunriseco\Properties\App\Routes\PropertyRoutes;



PropertyRoutes::routes();

/*
|--------------------------------------------------------------------------
| Fixed Asset Web Routes
|--------------------------------------------------------------------------
*/



Route::get('/', function () {
    return csrf_token();
});
