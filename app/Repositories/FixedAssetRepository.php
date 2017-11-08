<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 5:11 PM
 */

namespace App\Repositories;


use App\Models\FixedAssetModel;
use KielPack\LaraLibs\Base\AbstractRepository;

class FixedAssetRepository extends AbstractRepository
{

    protected function definedModel()
    {
        return new FixedAssetModel();
    }

}