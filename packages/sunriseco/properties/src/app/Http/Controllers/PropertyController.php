<?php

namespace Sunriseco\Properties\App\Http\Controllers;


use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use KielPack\LaraLibs\Supports\Result;


class PropertyController extends Controller
{

    protected $repo;

    function __construct(PropertyRepository $repo)
    {
        $this->$repo = $repo;
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

       return false;

    }

    public function store(Request $request) {
        
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
