<?php

namespace Sunriseco\Properties\App\Http\Controllers;




use Illuminate\Http\Request;
use KielPack\LaraLibs\Base\Controller;
use KielPack\LaraLibs\Supports\Result;
use Sunriseco\Properties\App\Http\Requests\PropertyForm;
use Sunriseco\Properties\App\Repositories\PropertyRepository;


class PropertyController extends Controller
{

    protected $repo;

    public function __construct(PropertyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all() {
        try {
            $properties =  $this->repo->getProperties();
            
            return Result::response(['properties' => $properties]);
        }
        catch(Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }

    public function create() {

        //create 
       return Result::response(['data' => csrf_token()]);
    }

    public function edit($id) {

        $model = $this->repo->find($id);

        return Result::response(['data' => $model]);
    }

    public function store(PropertyForm $request) {
        
        $inputs = $request->all();

        try {
            $this->repo->saveProperty($inputs);
            return Result::ok();
        }
        catch(Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }



}
