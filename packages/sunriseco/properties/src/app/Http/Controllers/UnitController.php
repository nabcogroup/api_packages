<?php

namespace Sunriseco\Properties\App\Http\Controllers;


use http\Exception;
use KielPack\LaraLibs\Base\Controller;
use KielPack\LaraLibs\Supports\Result;
use Sunriseco\Properties\App\Repositories\PropertyRepository;

class UnitController extends Controller
{

    private $repo;

    public function __construct(PropertyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create($id) {

        try {
            $property = $this->repo->find($id);
            $unit = $property->createNewUnit();
            return Result::response(['data' => $unit]);
        }
        catch(Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }




}