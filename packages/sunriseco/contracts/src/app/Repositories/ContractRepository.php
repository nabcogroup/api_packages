<?php

namespace Sunriseco\Contracts\App\Repositories;


use Carbon\Carbon;
use KielPack\LaraLibs\Base\AbstractRepository;
use KielPack\LaraLibs\Helpers\EDB;
use KielPack\LaraLibs\Supports\Facades\Result;
use KielPack\LaraLibs\Traits\PeriodTrait;
use KielPack\LaraLibs\Traits\SerializeTrait;
use KielPack\LaraLibs\Traits\PaginationTrait;
use KielPack\LaraLibs\Traits\StringTrait;

use Sunriseco\Contracts\App\Models\Contract as Contract;
use Sunriseco\Contracts\App\Services\RenewalPolicyService;

class ContractRepository extends AbstractRepository
{

    use PaginationTrait, SerializeTrait,StringTrait,PeriodTrait;

    protected $parameters;

    protected function beforeCreate(&$model, &$source)
    {
        if(!isset($model['contract_no'])) {
            $villaNo = $model['villa_no'];
            $this->cleanupAttributes('villa_no',$model);
            $model['contract_no'] = "C" . $villaNo . "-" . Carbon::now()->year . "-" . $this->model->createNewId();
        }
        $model['status'] = 'pending';
    }

    public function definedModel()
    {
        return new Contract();
    }

    public function create($defaultPeriod)
    {
        $model = $this->model->createInstance($defaultPeriod);

        return $model;
    }

    public function getContracts($filter_field = null, $filter_value = null)
    {

        $params = [];
        $edb = EDB::createQuery('contracts');

        $modelDb = $edb->joins([
            'villas' => 'contracts.villa_id=villas.id',
            'tenants' => 'contracts.tenant_id=tenants.id'
        ])->leftJoins(['contract_bills' => 'contract_bills.id=contract_bills.contract_id'])
        ->self($params);

        $modelDb = $modelDb->select(
            "contracts.id",
            "contracts.contract_no",
            "villas.villa_no",
            "tenants.full_name",
            "contracts.created_at AS contract_created",
            "contracts.period_start",
            "contracts.period_end",
            "contracts.free_days",
            "included_free_month",
            "contract_bills.bill_no",
            "contracts.amount",
            "contracts.status AS contracts_status")
            ->where('contracts.status', $state);

        return $this->createPagination($modelDb, function ($row) {

            $period_extended = $this->getPeriodExtensionDays($row->period_end,$row->free_days);

            $item = [
                "id"                    => $row->id,
                "contract_no"           => $row->contract_no,
                "villa_no"              => $row->villa_no,
                "full_name"             => $row->full_name,
                "created_at"            => Carbon::parse($row->contract_created)->format('d, M, Y'),
                "period"                => $this->getFullFormatPeriod($row->period_start,$period_extended,'d-M-Y'),
                "free_days"             => $row->free_days,
                "included_free_month"   =>  $row->free_month,
                "amount" => number_format($row->amount, 2),
                "status" => ucfirst($row->contracts_status)
            ];

            return $item;

        }, $params);

    }

    public function getExpiringContracts($start, $end)
    {
        return $this->model->getExpiringContracts($start, $end);
    }

    public function getExistingBill($contractNo)
    {
        return $this->model->where('contract_no', $contractNo)->with('bill')->firstOrFail();
    }

    public function saveContract($entity)
    {
        return $this->attach($entity, [], true)->instance();
    }

    public function getActiveContracts()
    {

        $this->model = $this->model->where('status', 'active');

        return $this;
    }

    public function findByContractNo($contractNo)
    {
        return $this->model->where('contract_no', $contractNo);
    }


    /********************************
     *  Renew contract but check if requirements have met
     *
     *  verify if can renew
     ******************************/
    public function renew($oldContract,$models)
    {
        $oldContractNo = $oldContract->contract_no;

        $this->cleanupAttributes('id',$models);

        preg_match('/^([^-]+?)-([0-9]+?)-([0-9]+?)$/', $oldContractNo, $splits);

        if (sizeof($splits) > 0) {
            array_splice($splits, 0, 1); //exclude the whole contractno
            $splits[1] = Carbon::now()->year;
            $splits[2] = $this->createNewId();
        }

        //set contract no
        $newContractNo = implode('-', $splits);

        $model['contract_no'] = $newContractNo + "-R";

        $this->attach($models);

        $oldContract->completed();

        $oldContract->save();

    }

    public function cancelled($models)
    {

        $contract = $this->single($models['id']);

        if ($contract->canCancel()) {

            $contract->cancel();

            $contract->save();

        }

        return true;
    }

    public function terminate($models = array())
    {
        $contract = $this->find($models['id']);

        if ($contract->isActive()) {

            $contract->terminate();
            $contract->saveWithUser();

            $totalBalance = $contract->total_balance;
            $contract->termination()->create([
                'description'   =>  $models['description'],
                'ref_no'        =>  $models['ref_no'],
                'total_balance' =>  $totalBalance
            ]);


            //cancelled all the payment
            $payments = $contract->received_payments->get();
            if ($payments->count() > 0) {
                foreach ($payments as $payment) {
                    $payment->setStatusToCancel();
                    $payment->save();
                }
            }
        } else {
            throw new Exception("Unable to terminate contract either contract is not active or internal error occured");
        }
        return $contract;
    }

    public function calculateAmount($ratePerMonth)
    {

        $this->model->toComputeAmount($ratePerMonth);
        return $this;
    }

    public function getEventCalendar($start, $end)
    {

        $contracts = $this->model
            ->with('tenant', 'villa')
            ->where('status', 'active')
            ->whereBetween("period_end_extended", [$start, $end])->get();


        $args = [
            "model" => "contract",
            "base_period" => "period_end_extended",
            "grace_period" => "3"
        ];

        return new CalendarService($contracts, $args);

    }


}