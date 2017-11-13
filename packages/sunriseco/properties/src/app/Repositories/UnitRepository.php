<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/9/2017
 * Time: 7:53 PM
 */

namespace Sunriseco\Properties\App\Repositories;


use KielPack\LaraLibs\Base\AbstractRepository;
use Sunriseco\Properties\App\Models\Villa;

class UnitRepository extends AbstractRepository
{

    protected function definedModel()
    {
        return new Villa();
    }
}