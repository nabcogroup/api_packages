<?php

namespace App\Repositories;


use Carbon\Carbon;
use Illuminate\Support\Collection;
use KielPack\LaraLibs\Base\AbstractRepository;
use KielPack\LaraLibs\Supports\Facades\Result;
use KielPack\LaraLibs\Traits\PaginationTrait;
use Mockery\Exception;
use Sunriseco\Properties\App\Models\Property;


class PropertyRepository extends AbstractRepository
{

    use PaginationTrait;

    protected $villaObject;
    
    protected function definedModel()
    {
        return new Property();
    }



    public function getProperties() {
        $params = [];
        $activeRecords = $this->model->customFilter($params)->select('code', 'name','address');
        return $this->createPagination($activeRecords,null,$params);
    }

    public function getAvailableProperties() {
        $activeRecords = $this->model->with(['villas' => function($query) {
                $query->where('status','vacant');
        }]);
        return $activeRecords->get();
    }

    public function saveProperty($request) {
        if(isset($request['villas'])) {
            $children = $request['villas'];
            unset($request['villas']);
        }
        else {
            $children = [];
        }
        $this->attach($request,$children);
    }


    public function updateVilla($request)
    {
        try {
            $villa = $this->find($request['id']);
            if($villa) {
                $villa->toMap($entity)->save();
            }
        }
        catch (Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }

        return true;
    }


    //override
    protected function afterCreate(&$model, $children = array())
    {
        if(!empty($children)) {
            $model->villas()->create($children);
        }
    }
}
