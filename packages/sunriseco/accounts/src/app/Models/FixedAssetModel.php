<?php

namespace Sunriseco\Accounts\App\Models;


use Carbon\Carbon;
use KielPack\LaraLibs\Base\BaseModel;

use KielPack\LaraLibs\Traits\MathTrait;
use Sunriseco\Accounts\App\Models\DepreciationModel;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;

class FixedAssetModel extends BaseModel
{
    use MathTrait;

    protected $table = "fixed_assets";

    protected $fillable = ["purchase_date","description","fixed_asset_type","property_code","cost","year_span","salvage_value"];

    protected $appends = [
        "full_fixed_asset_type",
        "full_property",
        "full_purchase_date",
    ];

    public static function createInstance() {

        return new FixedAssetModel();
    }


    public function __construct(array $attributes = [])
    {
        if(empty($attributes)) {
            $attributes = [
                'purchase_date' => Carbon::now()->toDateString(),
                'cost' => 0,
                'year_span' => 5,
                'salvage_value' => 0,
            ];
        }

        parent::__construct($attributes);
    }

    public function depreciations() {
        return $this->hasMany(DepreciationModel::class,'fixed_asset_id','id');
    }



    public function getFullFixedAssetTypeAttribute()
    {
        return Selection::getValue('fixed_asset_type', $this->fixed_asset_type);
    }

    //convert the property to full desc
    public function getFullPropertyAttribute()
    {
        return Selection::getValue('villa_location', $this->property_code);
    }

    //convert the purchase date to proper format date
    public function getFullPurchaseDateAttribute()
    {
        return Carbon::parse($this->purchase_date)->format('d-M-Y');
    }

    public function getTotalCostForDepAttribute() {
        $total_cost_for_dep = $this->cost - $this->salvage_value;
        return $total_cost_for_dep;
    }

    public function getDepreciationAmountAttribute() {
        return $this->breakApart($this->total_cost_for_dep,$this->year_span);
    }



}