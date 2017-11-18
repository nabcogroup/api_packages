<?php 


namespace Sunriseco\Contracts\App\Http\Routes;


use Illuminate\Support\Facades\Route;


class ContractRoutes {

    public static function routes() {
        Route::group(['prefix' => 'api/contract'],
            function() {
            
                Route::get('list',"Sunriseco\Contracts\App\Controllers\ContractController@all");
                Route::get('create', "Sunriseco\Contracts\App\Controllers\ContractController@create");
                Route::get('{id}/renew', "Sunriseco\Contracts\App\Controllers\ContractController@renew");
                
                Route::post('compute',"Sunriseco\Contracts\App\Controllers\ContractController@compute");
                Route::post('store',"Sunriseco\Contracts\App\Controllers\ContractController@store");
                Route::post('cancel', "Sunriseco\Contracts\App\Controllers\ContractController@cancel");
                Route::post('renew',"Sunriseco\Contracts\App\Controllers\ContractController@renew");
                Route::post('terminate',"Sunriseco\Contracts\App\Controllers\ContractController@terminate");

            }
        );

        Route::group(['prefix' => 'api/bill'],
            function() {

                Route::get('list',["uses" => "ContractBillController@all", "roles" => ["account"]]);
                Route::get('{contractNo}/create',["uses" => "ContractBillController@create", "roles" => ["contract","account"]]);
                Route::get('{billNo}/edit',["uses" => "ContractBillController@edit","roles" => ["account"]]);
                Route::get('api/bill/search/{filter?}/{value?}',["uses" => "ContractBillController@search", "roles" => ["account"]]);

                Route::post('store',["uses" => "ContractBillController@store", "roles" => ["contract","account"]]);
                Route::post('update',["uses" => "ContractBillController@update", "roles" => ["account"]]);
            });
    }
}