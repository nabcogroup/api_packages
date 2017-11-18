<?php

namespace App\Http\Controllers;

use App\Events\NotifyUpdate;
use App\Events\OnGetContract;

use App\Http\Requests\BillForm;
use App\Listeners\EmailPayment;
use App\Listeners\GetContract;
use App\Payment;
use App\Repositories\BankAccountRepository;
use App\Repositories\BillRepository;
use App\Repositories\ContractRepository;
use App\Selection;

use App\Services\Bundle;
use App\Services\EventListenerRegister;
use App\Services\Result;

use Carbon\Carbon;
use Dompdf\Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Traits\HelperTrait;

use PDF;

class ContractBillController extends Controller
{

    use HelperTrait;

    private $billRepository;
    private $contractRepository;
    private $bankAccountRepository;
    private $selection;

    public function __construct(
        BillRepository $repository,
        ContractRepository $contractRepo,
        BankAccountRepository $bankAccountRepository)
    {

        $this->billRepository = $repository;
        $this->contractRepository = $contractRepo;
        $this->bankAccountRepository = $bankAccountRepository;

        $this->selection = new Selection();
    }

    public function index() {

        return view("bill.index");
        
    }

    public function apiGetList(Request $request) {

        $inputs = $request->all();
        unset($inputs['page']);
        $bills = $this->billRepository->getPendingBills($inputs);

        return $bills;
    }

    public function create($contractNo) {
        
        $bundle = new Bundle();
        $bundle->add("contractNo", $contractNo);

        try {
            
            event(new OnGetContract($bundle, new EventListenerRegister(["GetContract"])));

            if(!$bundle->hasOutput()) {
                throw new Exception("Internal Error");
            }
            
            $contract = $bundle->getOutput("contract");

            if($contract) {
                
                $bill = $contract->bill()->with('payments')->first();
                
                if(!$bill) {
                    $bill = $this->billRepository->create($contract);
                }
            }
            else {
                throw new Exception("No contract found");
            }

            $lookups = $this->selection->getSelections(array("payment_mode","payment_term","payment_status","bank"));
            
            return view("bill.create",compact("contract","bill","lookups"));
        }
        catch(Exception $e) {
            return Result::badRequestWeb($e);
        }
    }

    public function apiCreate($contractNo) {
        try {

            //get the contract
            $bundle = new Bundle();
            $bundle->add("contractNo", $contractNo);
            event(new OnGetContract($bundle, new EventListenerRegister(["GetContract"])));
            if(!$bundle->hasOutput()) {
                throw new Exception("Internal Error");
            }
            $contract = $bundle->getOutput("contract");
            
            //create bill instance
            $bill = $this->billRepository->create($contract);
            $bill->instance->amount = $contract->payable_per_month;
            $lookups = $this->selection->getSelections(array("payment_mode","payment_term","payment_status","bank"));
            
            //return bill and contract
            return compact("bill","contract","lookups");

        }
        catch(Exception $e) {
            return Result::badRequest(["message" => $e->getMessage()]);
        }
    }

    /*********************************
     *  Method: apiStore
     *  Function: create and save the bill for the contract
     *  - get existing contract
     *  - payment must be entered before saving the contract
     *  - payment must tally with the amount in the contract
     *  -
     *************************************/
    public function apiStore(BillForm $request) {
        
        $inputs = $request->filterInput();
        
        try {

            $bundle = new Bundle();
            $bundle->add("contractId",$inputs['contract_id']);
            //is there any balance still
            if(!isset($inputs['payments'])) {
                throw new Exception('No payment was entered');
            }

            //get the contract
            event(new OnGetContract($bundle,new EventListenerRegister(["GetContract"])));
            $contract = $bundle->getOutput("contract");

            if(!$contract) throw new Exception("Internal error unable to find contract");

            if(!$contract->isReconcile($this->calculateTotal($inputs['payments']))) throw new Exception('Total payment amount is insufficient');

            //save bill
            $bill = $this->billRepository->saveBill($inputs,Auth::user()->getAuthIdentifier());
            
            //create an sms
            $smsArgs = [
                'mobile_no' =>  $contract->tenant()->first()->mobile_no,
                'message'   =>  "Your new Contract No: ".$contract->contract_no." Thank you!!!"
            ];

            $bundle = new Bundle();
            $bundle->add('contractId',$inputs['contract_id']);
            $bundle->add('contract',$contract);
            $bundle->add('smsArgs',$smsArgs);

            //notify update
            event(new NotifyUpdate($bundle,new EventListenerRegister(["UpdateContractStatus"])));

            return Result::ok('Successfully Save',["bill" => $bill]);
        }
        catch (Exception $e) {

            return Result::badRequest(["message" => $e->getMessage()]);

        }
    }

    /*********************************
     *  Method: show
     *  Function: Display bill on pdf type
     *  - get existing bill 
     *  - associate contract
     *  - display pdf
     *************************************/
    public function show($billNo) {
        //show
        try {

            $bill = $this->billRepository->includePayments()->findByBillNo($billNo)->first();
            
            if(!$bill) {
                throw new Exception("Internal Exception");
            }
            $bundle = new Bundle();
            $contractId = $bill->contract_id;
            $bundle->add("contractId", $contractId);

            event(new OnGetContract($bundle, new EventListenerRegister(["GetContractPayments"])));

            $contract = $bundle->getOutput("contract");

            $dompdf = PDF::loadView('bill.display', compact('bill', 'contract'));

            return $dompdf->stream(); //view('bill.display',compact('bill', 'contract'));
        }
        catch(Exception $e) {

            return Result::badRequestWeb($e);

        }
    }

    public function edit($billNo = null) {

        return view("bill.update",compact('billNo'));
    }

    public function apiEdit($billNo = null)  {

        if($billNo == null) {
            return Result::badRequest(["message" => "Invalid Bill No"]);
        }

        $bill = $this->billRepository->includePayments()->findByBillNo($billNo)->first();
        if(!$bill) {
            return Result::badRequest(["message" => "Invalid Bill No"]);
        }

        $paymentInstance = $bill->createInstanceOfPayment();

        //create payment summary
        $bundle = new Bundle();
        $contractId = $bill->contract_id;
        $bundle->add("contractId",$contractId);
        $isIncluded = true;
        $bundle->add("paymentIncluded",$isIncluded);

        event(new OnGetContract($bundle,new EventListenerRegister(["GetContract"])));

        $contract = $bundle->getOutput("contract");
        $lookups = $this->selection->getSelections(array("payment_mode","payment_term","payment_status","bank"));
        $lookups['bank_accounts'] = $this->bankAccountRepository->getAll();

        $paymentSummary = [
            "total_payment" => $bill->settled_amount,
            "total_cost" => $contract->amount
        ];

        return compact('bill','paymentSummary','contract','paymentInstance','lookups');
    }

    /*********************************
     *  Method: apiUpdate
     *  Function: create/update bill
     *  -
     *************************************/
    public function apiUpdate(BillForm $request) {

        try {

            $inputs = $request->filterInput();
            $currentBill = $this->billRepository->saveBill($inputs, Auth::user()->getAuthIdentifier());

//            $bundle = new Bundle();
//            $bundle->add("bill",$currentBill);
//            $bundle->add('smsArgs',$smsArgs);
//            event(new NotifyUpdate($bundle,new EventListenerRegister(["EmailPayment"])));

            return Result::ok('update successfully');

        }
        catch(Exception $e) {

            return Result::badRequest(["message" => $e->getMessage()]);

        }
    }

    public function apiSearch($filter='bill',$value='') {

        try {
            if ($filter == 'contract') {
                $field = 'contracts.contract_no';
            }
            else if($filter == 'villa') {
                $field = 'villas.villa_no';
            }
            else {
                $field = 'contract_bills.bill_no';
            }
            $results = $this->billRepository->search($field,$value);
            return $results;

        }
        catch(Exception $e) {

        }
    }


}
