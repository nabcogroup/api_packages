<?php

namespace KielPack\LaraLibs\Base\Traits;


trait RepoQueryTrait
{
    public function getAll() {
        return $this->model->all();
    }

    public function limit($limit) {
        return $this->model->limit($limit)->get();
    }

    public function get() {
        return $this->model->get();
    }

    public function single() {
        return $this->model->first();
    }

    public function instance() {
        return $this->model;
    }

    public function find($id){
        return $this->model->find($id);
    }

    public function explicitQuery($field,$value,$opt='=') {
        $this->model = $this->model->where($field,$opt,$value);
        return $this;
    }

    public function includes($childrens) {
        $this->model  = $this->model->with($childrens);
        return $this;

    }

}