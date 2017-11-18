<?php

namespace App\Repositories;

use App\Payment;
use App\Selection;

use Carbon\Carbon;
use Dompdf\Exception;
use App\Traits\UserTrait;
use App\Traits\PaginationTrait;

use App\Traits\QueryTemplateTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\QuerySoftDeleteTrait;
use App\Services\PaymentScheduleGeneratorService;

class BillRepository extends AbstractRepository
{

    use QuerySoftDeleteTrait, 
        PaginationTrait, 
        UserTrait,
        QueryTemplateTrait;
    
    protected function definedModel()
    {
        return new \App\ContractBill();
    }

    public function create($contract) {
        
        $contractId = $contract->getId();
        $model = $this->model->createInstance($contractId);
        $model->instance->amount = $contract->payable_per_month;

        //generate payment
        $days_total = $contract->getDiffDays();
        $configure = ($contract->configure) ?  $contract->configure : [];
        
        //count how many cycle
        $settings = [
            'total_month'       =>  abs(floor($days_total / 30)),
            'period_start'      =>  Carbon::parse($contract->period_start),
            'effectivity_date'  =>  isset($configure['prep_due_date']) ? Carbon::parse($contract->period_start)->addDays(floatval($configure['prep_due_date'])-1) : Carbon::parse($contract->period_start),
            'payment_no'        =>  isset($configure['prep_series']) ? floatval($configure['prep_series']) :  "Cash",
            'amount' =>  $contract->payable_per_month,
        ];

        $generator = new PaymentScheduleGeneratorService($settings);
        $payments = [];
        $generator->create(function(&$schedule) use ($configure,$contract,&$payments) {
            $schedule["reference_no"] = isset($configure['prep_ref_no']) ? $configure['prep_ref_no'] : '';
            $schedule["bank"] = isset($configure['prep_bank']) ? $configure['prep_bank'] : '';
            $schedule["payment_type"] =  $contract->contract_type == 'legalized' ? 'cheque' : 'cash';
            
            $payment = Payment::createInstance($schedule);
            array_push($payments,$payment);
        });

        $model->payments = $payments;
        return $model;
    }

    protected function beforeCreate(&$model,&$source) {
        $model['bill_no'] = "B" . $model['contract_id'] . "-" . Carbon::now()->year . "-" . $this->model->createNewId();

    }

    protected function afterCreate(&$model, $children = array())
    {

        $userId = $this->getCurrentUserId();
        if (!isset($children['id']) || $children['id'] == 0) {
            $model->Payments()->saveMany(array_map(function ($item) use ($userId) {
                $payment = new Payment();
                $payment->toMap($item);
                $payment->payment_no = $item['payment_no'];
                $payment->user_id = $userId;  //manually
                return $payment;
            }, $children));
        }
    }

    public function saveBill($entity = array(), $userId)
    {

        if (isset($entity['id']) && $entity['id'] != 0) {
            
            $bill = $this->model->find($entity['id']);
            //update its payment
            $entityPayments = isset($entity['payments']) ? $entity['payments'] : [];
            if (sizeof($entityPayments) > 0) {
                foreach ($entityPayments as $entityPayment) {
                    if (!isset($entityPayment['id']) || $entityPayment['id'] == 0) {
                        //insert payment
                        $paymentModel = new Payment();
                        $paymentModel->toMap($entityPayment);
                        $paymentModel->bill_id = $bill->getId();
                        $paymentModel->saveWithUser();
                    }
                    else {
                        //update only without received
                        $paymentModel = Payment::find($entityPayment['id']);
                        if ($paymentModel != null) {
                            //check if there is a replace payment value
                            if(isset($entityPayment['replace_ref'])) {
                                $paymentModel->replace_ref = $entityPayment['replace_ref'];
                                unset($entityPayment['replace_ref']);
                            }
                            $paymentModel->toMap($entityPayment);
                            $paymentModel->saveWithUser();
                        }
                        else {
                            throw new Exception('Invalid payment');
                        }
                    }
                }
            }
        }
        else {
            $payments = $entity['payments'];
            unset($entity['payments']);
            $bill = $this->attach($entity, $payments)->instance();
        }

        return $bill;

    }

    public function includePayments()
    {
        $this->model = $this->model->with("Payments");

        return $this;
    }


    public function findByBillNo($billNo)
    {

        return $this->model->where('bill_no', $billNo);
    }

    public function findExistingContract($contractNo)
    {
        return $this->model->getExistingContract($contractNo);
    }

    public function hasBalance(Array $payments, $contractAmount)
    {
        if (sizeof($payments) > 0) {

            $amountReceived = 0;
            foreach ($payments as $payment) {
                $amountReceived += (float)$payment['amount'];
            }

            if ($amountReceived >= (float)$contractAmount) {
                return false;
            }
        }
        return true;
    }

    public function search($field, $value)
    {
        $dbraw = $this->createDb('contracts')
            ->join('contract_bills', 'contract_bills.contract_id', '=', 'contracts.id')
            ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
            ->join('villas', 'contracts.villa_id', '=', 'villas.id')
            ->select('contracts.contract_no', 'villas.villa_no', 'contracts.id', 'tenants.code', 'tenants.full_name', 'contract_bills.bill_no')
            ->where('contracts.status', 'active');

        if ($field == 'villas.villa_no') {
            $dbraw = $dbraw->where($field, '=', $value);
        }
        else {
            $dbraw = $dbraw->where($field, 'LIKE', $value . '%');
        }

        return $dbraw->get();

    }

    public function getPendingBills($filters = array())
    {

        $locations = Selection::getSelections(["villa_location"]);
        $dbraw = $this->createDb('contracts')
            ->join('contract_bills', 'contract_bills.contract_id', '=', 'contracts.id')
            ->join('tenants', 'tenants.id', '=', 'contracts.tenant_id')
            ->join('villas', 'villas.id', '=', 'contracts.villa_id')
            ->select(
                'contract_bills.bill_no',
                'villas.location as location',
                'villas.villa_no as villa_no',
                'tenants.full_name', 'contracts.contract_no',
                'contracts.period_start', 'contracts.period_end_extended',
                'contract_bills.id as contracts_bill_id',
                'contracts.amount as contract_amount',
                \DB::raw("(SELECT SUM(amount) FROM payments WHERE status = 'clear' AND bill_id =   contracts_bill_id) as payment_amount"))
            ->groupBy(
                'contract_bills.bill_no',
                'villas.location',
                'villas.villa_no',
                'contracts.amount')
            ->where('contracts.status','active');
            
        if (isset($filters['filter_field'])) {
            if($filters['filter_field'] == 'full_location') {
                $filter_locations = Selection::where('category', 'villa_location')->where('name','LIKE','%'.$filters['filter_value'].'%')->get();
                foreach ($filter_locations as $filter_location) {
                    $dbraw = $dbraw->where('villas.location', $filter_location->code);
                }
            }
            else {
                $dbraw = $dbraw->where($filters['filter_field'], 'LIKE', '%' . $filters['filter_value'] . '%');
            }
        }

        return $this->createPagination($dbraw, function ($row)  {
            $item = [
                'bill_no'           => $row->bill_no,
                'villa_no'          => $row->villa_no,
                'contract_no'       => $row->contract_no,
                'full_name'         => $row->full_name,
                'period'            => Carbon::parse($row->period_start)->format('d M Y') . '-' . Carbon::parse($row->period_end_extended)->format('d M Y'),
                'contract_amount'   => number_format($row->contract_amount, 2),
                'total_payment'     => number_format($row->payment_amount, 2),
                'total_balance'     => number_format((floatval($row->contract_amount) - $row->payment_amount), 2),
                'full_location'     => Selection::getValue('villa_location',$row->location)
            ];
            
            return $item;
            
        }, $filters);

    }

}