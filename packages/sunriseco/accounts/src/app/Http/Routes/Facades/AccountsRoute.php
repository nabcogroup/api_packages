<?php

namespace Sunriseco\Accounts\App\Http\Routes\Facades;


use Illuminate\Support\Facades\Facade;

class AccountsRoute extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'accountsRoute';
    }
}