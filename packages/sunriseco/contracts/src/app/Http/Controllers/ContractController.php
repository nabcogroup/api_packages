<?php

namespace Sunriseco\Contracts\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use KielPack\LaraLibs\Supports\Result;

use KielPack\LaraLibs\Supports\Facades\Bundle;
use Kielpack\Laralibs\App\Events\OnPullRequest;
use Sunriseco\Contracts\App\Http\Requests\ContractForm;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;
use KielPack\LaraLibs\Supports\Facades\EventListenerRegister;


class ContractController extends Controller
{
    private $contractRepo;

    const DEFAULT_PERIOD = 12;
    const DEFAULT_EXPIRED_PERIOD = 3;

    public function __construct(ContractRepository $repository)
    {
        $this->contractRepo = $repository;
    }
   

    /**********************************
     *
     * GET: api/contract/create
     * */
    public function create()
    {
        try {

            $outputs = array();

            $data = $this->contractRepo->create(self::DEFAULT_PERIOD);

            $lookups = Selection::getSelections(["contract_type","tenant_type"]);

            $bundle = new Bundle();
            
            //not created yet
            event(new OnPullRequest($bundle, new EventListenerRegister(["GetProperty"])));

            $properties = $bundle->getOutput("property");

            return compact("data", "lookups","properties");

        }
        catch (Exception $e) {

            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }

    public function store(ContractForm $request) {

        $inputs = $request->filterInput();
        try {

            $bundle = new Bundle();

            $bundle->add('tenant', $inputs['register_tenant']);
            $bundle->add('villaId', $inputs['villa_id']);

            event(new OnCreating($bundle, new EventListenerRegister(["GetVilla", "CreateTenant"])));

            if (!$bundle->hasOutput()) {
                throw new Exception("Internal Error");
            }

            $tenantOutput = $bundle->getOutput('tenant');
            $villaOutput = $bundle->getOutput('villa');

            $inputs['tenant_id'] = $tenantOutput->id;
            $inputs['villa_no']  = $villaOutput->villa_no;

            $contract = $this->contractRepo->saveContract($inputs);
            $bundle->clearAll();
            $bundle->add('villa', ['id' => $contract->villa_id,'status' => 'occupied']);

            event(new NotifyUpdate($bundle, new EventListenerRegister(["UpdateVillaStatus","CreatePaymentSchedule"])));

            return Result::ok("Successfully save!!", ["id" => $contract->contract_no]);

        }
        catch (Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }


    /****************************
     *
     * Show the balance in contract
     * GET: api/contract/{id}/balance
     * ****/
    public function balance($id) {

        $contract = $this->contractRepo->find($id);
        try {
            if ($contract)
                return Result::response(['balance' => $contract->total_balance]);
            else
                throw new Exception('Invalid Contract');
        }
        catch(Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }

    /****************************
     *
     * Create a new contract for renewal
     * GET: api/contract/{id}/renew
     * ****/
    public function renew(Request $request)
    {
        try {

            //get the old contract including villa
            $currentContract = $this->contractRepo->includes('tenants','villas')->find($id);

            //make sure the contract is active
            if (!$currentContract->isActive()) {
                throw new Exception('Contract is not active');
            }

            //recalculate the past contract period
            //$remainingPeriodDay = $oldContract->getRemainingPeriod();

            //display contract
            $currentContract->setDefaultPeriod(\Carbon\Carbon::parse($oldContract->period_extended), self::DEFAULT_PERIOD);

            $display = [
                'tenant'                =>  $currentContract->getOwner()->full_name,
                'villa_no'              =>  $currentContract->getCurrentVilla()->villa_no,
                'period_start'          =>  $currentContract->period_start,
                'period_end'            =>  $currentContract->period_end,
                'recurring_contract'    =>  $currentContract->recurring_contract,
                'free_days'             =>  $currentContract->free_days,
                'included_free_months'  =>  $currentContract->included_free_months,
                'total_balance'         =>  $currentContract->total_balance
            ];

            return compact("display");
        }
        catch (Exception $e) {

            return Result::badRequest(['message' => $e->getMessage()]);

        }
    }

    public function update(Request $renewal) {
        try {

            $entity = $renewal->all();
            $id = $entity['id'];
            $this->
            $oldContract = $this->contractRepo->find($entity['id']);
            if ($oldContract) {
                //make sure the contract is active
                if (!$oldContract->isActive()) {
                    throw new Exception('Contract is not active');
                }

                $entity['villa_id'] = $oldContract->villa_id;
                $entity['tenant_id'] = $oldContract->tenant_id;
                $entity['period_start'] = Carbon::parse($entity['period_start']);
                $entity['period_end'] = Carbon::parse($entity['period_end']);
                $newContract = $this->contractRepo->renew($oldContract,$entity);

                $bundle = new Bundle();
                $bundle->add('forwarded_balance',$oldContract->total_balance);
                $bundle->add('contract',$newContract);

                //not finished yet
                event(new OnCreating($bundle, new EventListenerRegister(["CreatePaymentSchedule"])));


            }

            return Result::ok("Successfully update!!!", ["id" => $newContract->contract_no]);

        }
        catch (Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }


    public function all(Request $request)
    {

        try {
            //get user contractsp
            $contracts = $this->contractRepo->getContracts($request->input('filter_field'), $request->input('filter_value'));
            //evaluate contract pending
            return $contracts;
        } 
        catch (Exception $e) {
            Result::badRequest(["message" => $e->getMessage()]);
        }
    }
    

    public function valueCalculation(ContractCalcForm $request)
    {

        $inputs = $request->filterInput();

        try {
            $ratePerMonth = floatval($inputs["custom_rate"]);

            //check if there is a custom calculation
            if ($ratePerMonth == 0) {
                //fire event
                $bundle = new Bundle();
                $bundle->add("villaId", $inputs['villa_id']);
                event(new OnCalculation($bundle, new EventListenerRegister(["GetVillaOnRecalculate"])));
                $villaOutput = $bundle->getOutput('villa');
                if ($villaOutput != null) {
                    $ratePerMonth = $villaOutput->rate_per_month;
                }
            }
            //create contract with new rate
            $contract = $this->contractRepo->create(self::DEFAULT_PERIOD);
            $contract->setPeriod($inputs['period_start'], $inputs['period_end']);
            $contract->toComputeAmount($ratePerMonth);
            return $contract;
        } catch (Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }


    public function cancel(Request $request)
    {
        try {
            $contract = $this->contractRepo->find($request->input('id'));
            if ($contract->isPending()) {
                $tenantId = $contract->tenant_id;
                $villaId = $contract->villa_id;
                $contract->cancel();
                $contract->save();
                //cancel and delete
                $contract->delete();

                $bundle = new Bundle();
                $bundleValue = ["id" => $villaId, "status" => "vacant"];
                $bundle->add("villa", $bundleValue);
                event(new NotifyUpdate($bundle, new EventListenerRegister(["UpdateVillaStatus"])));

                return Result::ok('Succefully cancelled!!!');
            } else {
                throw new Exception("Unable to cancel the contract");
            }
        } catch (Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }


    public function terminate(Request $request)
    {
        try {

            $inputs = $request->all();
            
            //validate password
            $userId = Auth::user()->getAuthIdentifier();
            $user = User::find($userId);
            
            //verify password
            if (!$user->isPasswordMatch($inputs['password'])) {
                throw new Exception('Password does not match');
            }

            $contract = $this->contractRepo->terminate($inputs);
            
            //terminate the contract
            if ($contract->isTerminated()) {
                //update villa event
                $bundle = new Bundle();
                $bundleValue = ["id" => $contract->villa()->first()->id, "status" => "vacant"];
                
                $bundle->add("villa", $bundleValue);
                $bundle->add('contract', $contract);
                $bundle->add('user', $user);
                
                event(new NotifyUpdate($bundle, new EventListenerRegister(["UpdateVillaStatus","UpdatePayment"])));
                
                return Result::ok('Succefully terminated!!!');
            } else {
                throw new Exception('Contract failed to terminate');
            }
        } 
        catch (Exception $e) {
            return Result::badRequest(['message' => $e->getMessage()]);
        }
    }
    
}
