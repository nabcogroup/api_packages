<?php

namespace KielPack\PropertyLease\App\Http\Controllers;



use Illuminate\Http\Request;


use KielPack\LaraLibs\Supports\Result;
use KielPack\LaraLibs\Traits\PaginationTrait;
use KielPack\PropertyLease\App\Repositories\FixedAssetRepository;


class FixedAssetController extends Controller
{
    use PaginationTrait;


    private $repo;

    public function __construct(FixedAssetRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all(Request $request, $property = null) {

        return $this->repo->getAssets($request,$property);

    }


    public function store(Request $request) {

        $this->validateRequest($request);

        $this->repo->saveFixedAsset($request);

        Result::ok('Successfully Save');
    }


    protected function validateRequest(Request $request) {

        $this->validate($request,[
            'purchase_date'     =>  'required|date',
            'description'       =>  'required',
            'property'          =>  'required',
            'fixed_asset_type'  =>  'required',
            'year_span'         =>  'required|integer|min:1',
            'salvage_value'     =>  'required',
            'cost'              =>  array("required","regex:/^\d+?|^\d+\.\d{2}?/")
        ]);
    }







}
