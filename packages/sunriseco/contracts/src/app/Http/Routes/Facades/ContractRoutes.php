<?php 

namespace Sunriseco\Contracts\App\Http\Routes\Facades;

use Illuminate\Support\Facades\Facade;

class ContractRoutes extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'contractRoutes';
    }
}