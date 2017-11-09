<?php

namespace Sunriseco\Properties\App\Models;

use Carbon\Carbon;
use KielPack\LaraLibs\Base\BaseModel;

class VillaGallery extends BaseModel
{
    protected $table = "villa_galleries";

    protected $appends = ['delete_mark'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
    }


    protected function getDeleteMarkAttribute() {
        return isset($this->appends['delete_mark']) ? $this->appends['delete_mark'] : false;
    }


    public function setDeleteMarkAttribute($value) {
        $this->appends['delete_mark'] = $value;
    }

}
