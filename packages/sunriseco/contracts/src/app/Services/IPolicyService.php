<?php

namespace Sunriseco\Contracts\App\Services;


interface IPolicyService
{
    public function execute();

    public function isValid();
}