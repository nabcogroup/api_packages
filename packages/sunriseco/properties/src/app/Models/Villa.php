<?php

namespace Sunriseco\Properties\App\Models;


use KielPack\LaraLibs\Base\BaseModel;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;


class Villa extends BaseModel
{
    
    protected $table = 'villas';

    protected $fillable = ["villa_no","location","electricity_no","water_no","qtel_no","villa_class","capacity","description","rate_per_month","status"];

    protected $appends = ['full_rate_per_month','full_villa_class','full_status'];

    public static function createInstance() {

        $villa =  new Villa();

        $villa->lookups = Selection::getSelections(['villa_type']);

        return $villa;
    }

    public function __construct(array $attributes = []) {

        if(empty($attributes)) {
            $attributes['rate_per_month'] = 0;
        }

        parent::__construct($attributes);

    }


    /**********************
     *  Contracts
     *
     * */
    public function contracts() {

    }

    public function activeContract() {

    }

    public function vacantList() {
        return $this->where('status','vacant');
    }

    public function occupiedList() {
        return $this->where('status','occupied');
    }





    // public function getTenant() {
        
    //     //communicate to contract using domain event
    //     // Event::raise(new onPullRequest($bundle,new EventRegisterListener(['getTenant'])))
    //     //use memoizationfor faster response
    //     if(!Memcache::exist('dsTenants')) {
    //         MemCache::push('dsTenant',Event::raise(new onPullRequest($bundle,new EventRegisterListener(['getTenant']))));        
    //     }

    //     return MemCache::pull('dsTenants');
    // }



    //*******************mutator and accessor****************************
    protected function getFullRatePerMonthAttribute() {
        return number_format($this->rate_per_month,2)." ".config("app.CURRENCY_FORMAT");
    }

    public function getFullVillaClassAttribute() {
        return Selection::convertCode($this->villa_class);
    }

    public function getFullStatusAttribute() {
        return Selection::convertCode($this->status);
    }

    public function getStatusCountAttribute() {
        return $this->select('status',\DB::raw('count(id) as count'))->groupBy('status')->get();
    }

    

    


    public function vacantOnly() {
        return $this->where('status','vacant');
    }


    public function setToVacant() {
        $this->setStatus('vacant');
    }

    public function setToOccupied() {
        $this->setStatus('occupied');
    }



}
