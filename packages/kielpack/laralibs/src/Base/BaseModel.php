<?php 


namespace KielPack\LaraLibs\Base;

use Illuminate\Database\Eloquent\Model;
use KielPack\LaraLibs\Base\Traits\StatusTrait;
use KielPack\LaraLibs\Base\Traits\BaseModelSaveTrait;


class BaseModel extends Model {

    use BaseModelSaveTrait, StatusTrait;

    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    protected function beforeSave() {return false;}
    protected function afterSave() {return false;}
   

    public function toMap($fields = array()) {
        if(sizeof($fields) > 0) {
            foreach ($fields as $key => $value) {
                if($this->isFillable($key) && !in_array($key,$this->appends)) {
                    $this->{$key} = $value;
                }
            }
        }

        return $this;
    }

   
    //chaining
    public function explicitSearch($fieldKey,$fieldValue) {
        return $this->where($fieldKey,$fieldValue);
    }

    public function customFilter(&$params,$callback = null) {

        if(request('filter_field',false)) {
            
            $filter_field = request('filter_field');
            $filter_value = request('filter_value');

            $params['filter_field'] = $filter_field;
            $params['filter_value'] = $filter_value;

            if(is_callable($callback)) {
                $filters = $callback($this,$filter_field,$filter_value);
            }
            else {
                return $this->where($filter_field,'LIKE','%'.$filter_value.'%');
            }
        }

        return $this;
    }

    public function createNewId() {

        $lastRecord = $this->orderBy('id','desc')->first();
        if($lastRecord == null)
            return 1;

        return $lastRecord->id++;
    }

    public function getId() {
        return $this->id;
    }


}
