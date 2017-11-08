<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 3:59 PM
 */

namespace KielPack\LaraLibs\Supports;


class Bundle
{

    protected $bundles = array();
    protected $outputs = array();

    public function add($name,$value) {
        $this->bundles[$name] = $value;
    }

    public function addOutput($name,$value) {
        $this->outputs[$name] = $value;
    }

    public function getOutput($name) {
        return isset($this->outputs[$name]) ? $this->outputs[$name] : null;
    }

    public function get($name) {
        return isset($this->bundles[$name]) ? $this->bundles[$name] : null;
    }

    public function hasOutput() {
        return (sizeof($this->outputs) > 0) ? true : false;
    }

    public function clearAll() {
        $this->bundles = array();
        $this->outputs = array();
    }
}