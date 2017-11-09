<?php




/*
|--------------------------------------------------------------------------
| Fixed Asset Web Routes
|--------------------------------------------------------------------------
*/
PropertyLeaseRoute::fixedAssetRoute();





Route::get('/', function () {
    return csrf_token();
});
