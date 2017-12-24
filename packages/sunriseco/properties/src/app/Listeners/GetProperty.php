<?php

namespace Sunriseco\Properties\App\Listeners;

use Kielpack\Laralibs\App\Events\OnPullRequest;
use Sunriseco\Properties\App\Repositories\PropertyRepository;

class GetProperty
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnRegister  $event
     * @return void
     */
    public function handle(OnPullRequest $event)
    {
        if($event->eventListener->isRegistered("GetProperty")) {

            $repo = new PropertyRepository();

            $properties = $repo->includes(["villas"])->getAll();

            $event->bundle->addOutput("properties",$properties);
        }
    }
}
