<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:13 PM
 */

namespace KielPack\LaraLibs\Supports;


class EventListenerRegister
{
    private $eventNames;

    public function __construct($eventNames = array())
    {
        $this->eventNames = $eventNames;
    }

    public function isRegistered($name) {

        foreach($this->eventNames as $eventName) {
            if(strtolower($eventName) == strtolower($name)) return true;
        }

        return false;
    }

}