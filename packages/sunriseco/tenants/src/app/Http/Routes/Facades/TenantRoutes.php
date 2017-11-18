<?php

namespace Sunriseco\Tenants\App\Http\Routes\Facades;

use Illuminate\Support\Facades\Facade;

class TenantRoutes extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'tenantRoutes';
    }
}