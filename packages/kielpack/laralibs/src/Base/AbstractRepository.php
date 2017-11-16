<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 12:14 PM
 */

namespace KielPack\LaraLibs\Base;



use KielPack\LaraLibs\Base\Traits\RepoQueryTrait;
use Mockery\Exception;

abstract class AbstractRepository
{
    use RepoQueryTrait;

    protected $model;

    protected abstract function definedModel();

    protected function beforeCreate(&$model,&$source) {return false;}
    protected function afterCreate(&$model,$children = array()) {return false;}
    protected function beforeUpdate(&$model) {return false;}
    protected function afterUpdate(&$model,$children = array()) {return false;}

    public function __construct($id = null) {

        $this->model = $this->definedModel();

        if($id != null) {
            $this->model = $this->model->find($id);
        }
    }


    //deprecated
    public function attach($model,$children = array(),$includeUser=false) {

        $childrenModel = [];

        if($this->isEditMode($model)) {
            $state = "modify";
        }
        else {
            $state = "create";
        }

        try {
            if($state == "create") {
                
                $this->model = $this->definedModel();
                
                $this->beforeCreate($model,$this->model);
                
                $this->model->toMap($model);

                if($includeUser)
                    $this->model->saveWithUser();
                else
                    $this->model->save();

                $this->afterCreate($this->model,$children);

            }
            else if($state == "modify") {
                
                $this->beforeUpdate($model);
                
                $this->model = $this->model->find($model['id']);
                
                $this->model->toMap($model);

                if($includeUser)
                    $this->model->saveWithUser();
                else
                    $this->model->save();

                $this->afterUpdate($this->model,$children);
            }
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $this;
    }

    //new
    public function save($request) {

    }


    public function isEditMode($request) {
        return (isset($request['id']) && $request['id'] > 0);
    }


}