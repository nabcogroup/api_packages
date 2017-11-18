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

    public function contractCalendar() {

        return view("contract.calendar");
    }

    public function contract(Request $request) {
        try {
            $periods = $request->all();
            $contracts = $this->repository->includeAssociates()
                ->activeOnly()
                ->getExpiryContracts(Carbon::parse($periods['start']), Carbon::parse($periods['end']))
                ->get();

            $events = array();

            if ($contracts) {
                foreach ($contracts as $contract) {
                    $event = [
                        "contract"      =>  $contract,
                        "id"            =>  $contract->getId(),
                        "contract_no"   =>  $contract->contract_no,
                        "full_name"   =>  $contract->tenant()->first()->full_name,
                        "period"        =>  ["start" => $contract->period_start, "end" => $contract->period_end_extended],
                        "title"         =>  $contract->villa()->first()->villa_no ." - ".$contract->tenant()->first()->full_name,
                        "start"         =>  Carbon::parse($contract->period_end_extended)->subDays(self::DEFAULT_EXPIRED_PERIOD)->toDateString(),
                        "end"           =>  Carbon::parse($contract->period_end_extended)->toDateString(),
                        "canRenew"      =>  true,
                    ];

                    array_push($events, $event);
                }
            }

            return $events;
        }
        catch (Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }

    


}
