<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/8/2017
 * Time: 11:14 AM
 */

namespace KielPack\PropertyLease\App\Http\Routes\Facade;


use Illuminate\Support\Facades\Facade;

class PropertyLeaseRoute extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'propertyLeaseRoute';
    }
}