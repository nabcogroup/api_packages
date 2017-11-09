<?php

namespace KielPack\LaraLibs\Base\Traits;

trait StatusTrait {

    public function hasStatusOf($status) {
        return $this->status == $status;
    }

    public function isActive() {
        return $this->status == 'active';
    }

    public function setStatus($status) {
        $this->status = $status;
    }
    

}