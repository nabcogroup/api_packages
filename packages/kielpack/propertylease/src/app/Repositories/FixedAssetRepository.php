<?php


namespace KielPack\PropertyLease\App\Repositories;


use Carbon\Carbon;
use Illuminate\Http\Request;

use KielPack\LaraLibs\Base\AbstractRepository;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;
use KielPack\LaraLibs\Supports\Result;

use KielPack\LaraLibs\Traits\PaginationTrait;
use KielPack\PropertyLease\App\Models\FixedAssetModel;

class FixedAssetRepository extends AbstractRepository
{

    use PaginationTrait;

    protected function definedModel()
    {
        return new FixedAssetModel();
    }


    public function getAssets(Request $request,$property) {

        $fixedAssets = $this->model->orderBy('description');

        $filter_field = $request->input('filter_field',null);
        $filter_value = $request->input('filter_value',null);

        if (!is_null($filter_field)) {
            if ($filter_field == 'fixed_asset_type') {
                $selection = Selection::where('category', 'fixed_asset_type')->where('name', 'like', '%'.$filter_value.'%')->first();
                $filter_value = (!is_null($selection)) ? $selection->code : '';
            }

            $fixedAssets = $fixedAssets->where($filter_field, 'like', '%'.$filter_value.'%');
        }

        if (!is_null($property)) {
            $fixedAssets->where('property', $property);
            $request->request->add(['property' => $property]);
        }


        return $this->createPagination($fixedAssets, function ($row) {

            $item = [
                "id"                    =>  $row->id,
                "purchase_date"          =>  $row->purchase_date,
                "full_purchase_date"    =>  $row->full_purchase_date,
                "description"           =>  $row->description,
                "fixed_asset_type"      =>  $row->fixed_asset_type,
                "full_fixed_asset_type" =>  $row->full_fixed_asset_type,
                "property"              =>  $row->property,
                "full_property"         =>  $row->full_property,
                "cost"                  =>  $row->cost,
                "tag_code"              =>  $row->tag_code
            ];

            return $item;

        },$request->all());
    }

    public function saveFixedAsset(Request $request)
    {
        try {

            $model = $this->attach($request->all())->instance();
            $attributes = [
                'ob_amount'                 =>  $this->model->total_cost_for_dep,
                'ob_year'                   =>  Carbon::parse($this->model->purchase_date)->year,
                'depreciated_value'         =>  $this->model->depreciation_amount,
                'acct_code'                 =>  '100',
                'book_value'                =>  $this->model->total_cost_for_dep,
            ];

            $num_year = $model->year_span;

            for ($i =0; $i < $num_year; $i++) {

                $depreciation = $model->depreciations()->create($attributes);

                $attributes['ob_amount'] = $attributes['ob_amount'] - $attributes['depreciated_value'];
                $attributes['ob_year'] = $attributes['ob_year'] + 1;
                $attributes['book_value'] = $attributes['book_value'] - $attributes['depreciated_value'];
            }

            //create depreciation table
            return true;
        }
        catch(Exception $e) {
            Result::badRequest(['message' => $e->getMessage()]);
        }
    }


}