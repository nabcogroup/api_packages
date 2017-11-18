<?php 

namespace  KielPack\Laralibs\Repositories;

use KielPack\LaraLibs\Repositories\IRepositories;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

abstract class GeneralRepository implements IRepositories {


    protected $query;

    protected $container;


    abstract public function getModelName() : string;

    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    protected function init() {
        return $this->makeQuery();
    }

    public function all() : \Illuminate\Support\Collection
    {
        return $this->query->get();
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findBy($field, $value)
    {
        // TODO: Implement findBy() method.
    }

    public function paginate($perPage = 20)
    {
        // TODO: Implement paginate() method.
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    protected function newQuery() : QueryBuilder {

    }


}
