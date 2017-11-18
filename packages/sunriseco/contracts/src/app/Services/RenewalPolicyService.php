<?php

namespace Sunriseco\Contracts\App\Services;


class RenewalPolicyService implements IPolicyService
{
    protected $attributes;

    public function __construct($attributes = array())
    {
        $this->attributes = $attributes;
    }

    public function execute()
    {

    }

    public function isValid()
    {

    }


    protected function paymentValidation() {


    }
}