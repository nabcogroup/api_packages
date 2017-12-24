<?php

namespace App\Http\Controllers;

use App\Repositories\ContractRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{

    const DEFAULT_EXPIRED_PERIOD = 3;

    public $repository;

    public function __construct(ContractRepository $repository)
    {
        $this->middleware(['auth:api']);

        $this->repository = $repository;

    }



    public function expiry(Request $request) {
        try {
            $periods = $request->all();
            $eventCalendar  = $this->contractRepo->getEventCalendar($periods['start'],$periods['end']);
            $eventCalendar->create(function($collection,&$event) {
                $event["title"] = $collection->villa()->first()->villa_no ." - ".$collection->tenant()->first()->full_name;
                $event["id"]    = $collection->getId();
                $event["full_name"]   =  $collection->tenant()->first()->full_name;
                $event["contract_no"]   =  $collection->contract_no;
            });

            return $eventCalendar->getEvents();
        } catch (Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }

    


}
