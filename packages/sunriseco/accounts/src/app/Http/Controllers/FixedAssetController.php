<?php

namespace Sunriseco\Accounts\App\Http\Controllers;



use Illuminate\Http\Request;


use KielPack\LaraLibs\Base\Controller;
use KielPack\LaraLibs\Traits\PaginationTrait;
use KielPack\LaraLibs\Supports\Facades\Result;

use Sunriseco\Accounts\App\Models\FixedAssetModel;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;
use Sunriseco\Accounts\App\Repositories\FixedAssetRepository;


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


    public function create() {

        $instance = FixedAssetModel::createInstance();
        $lookups = Selection::getSelections(['villa_location','fixed_asset_type']);

        return Result::response(['instance' => $instance,'lookups' => $lookups]);

    }

    public function show($id) {

        $fixedAsset = $this->repo->includes('depreciations')->find($id);
        $lookups = Selection::getSelections(['villa_location','fixed_asset_type']);

        return  Result::response(['instance' => $fixedAsset,'lookups' => $lookups]);

    }

    public function remove() {

    }

    protected function validateRequest(Request $request) {

        $this->validate($request,[
            'purchase_date'     =>  'required|date',
            'description'       =>  'required',
            'property_code'          =>  'required',
            'fixed_asset_type'  =>  'required',
            'year_span'         =>  'required|integer|min:1',
            'salvage_value'     =>  'required',
            'cost'              =>  array("required","regex:/^\d+?|^\d+\.\d{2}?/")
        ]);
    }







}
