<?php 


namespace KielPack\LaraLibs\Supports\Facades;


use Illuminate\Support\Facades\Facade;

class Bundle extends  Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bundle';
    }

}