<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 5:29 PM
 */

namespace Sunriseco\Properties\App\Routes\Facades;


use Illuminate\Support\Facades\Facade;

class PropertyRoutes extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'propertyRoutes';
    }
}