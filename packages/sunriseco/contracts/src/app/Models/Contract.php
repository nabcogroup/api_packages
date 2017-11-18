<?php

namespace Sunriseco\Contracts\App\Models;

use App\ContractBill;
use App\ContractTermination;
use Carbon\Carbon;


use Illuminate\Database\Eloquent\SoftDeletes;

use KielPack\LaraLibs\Base\BaseModel;
use KielPack\LaraLibs\Selections\SelectionModel as Selection;
use KielPack\LaraLibs\Traits\PeriodTrait;
use KielPack\LaraLibs\Traits\SerializeTrait;


use Sunriseco\Contracts\App\Services\RenewalPolicyService;
use Sunriseco\Properties\App\Models\Villa;


class Contract extends BaseModel
{
    use SoftDeletes,
        PeriodTrait,
        SerializeTrait;


    protected $table = "contracts";

    protected $fillable = [
        'contract_no',
        'contract_type',
        'period_start',
        'period_end',
        'free_days',
        'included_month',
        'additional_month',
        'amount',
        'villa_id',
        'tenant_id',
        'status'];

    protected $appends = ['full_status', 'full_contract_type', 'payable_per_month', 'full_period_start', 'full_period_end', 'total_year_month'];

    protected $hidden = ['deleted_at'];


    //factory method
    public static function createInstance($defaultMonths)
    {
        $contract = new Contract();
        $contract->setDefaultPeriod(Carbon::now(), $defaultMonths);
        return $contract;
    }


    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);
    }

    /******** mutators ********/
    protected function getFullStatusAttribute()
    {
        return Selection::convertCode($this->status);
    }

    protected function getFullContractTypeAttribute()
    {
        return Selection::convertCode($this->contract_type);
    }

    protected function getPayablePerMonthAttribute()
    {
        if ($this->amount > 0) {

            $totalAmountPerDays = $this->calculatePayableAmount($this->period_start, $this->period_end, $this->amount);

            return $totalAmountPerDays;
        }

        return 0;
    }

    protected function getFullPeriodStartAttribute()
    {
        return Carbon::parse($this->period_start)->toDateTimeString();
    }

    protected function getFullPeriodEndAttribute()
    {
        return Carbon::parse($this->period_end)->addDays($this->extra_days)->toDateTimeString();
    }

    /**********************************************
     * Total Contract Value Amount per period
     * *****************************************/
    protected function getTotalYearMonthAttribute()
    {
        return $this->calculateTotalYearMonth($this->period_start, $this->period_end);
    }

    /**********************************************
     * Received Payments collection
     * *****************************************/
    protected function getReceivedPaymentsAttributes()
    {
        $payments = $this->bill()->firstOrFail()->payments()->where('status', 'received');

        return $payments;
    }


    /**********************************************
     * Clear Payments collection
     * *****************************************/
    protected function getClearPaymentsAttribute()
    {

        $payments = $this->bill()->firstOrFail()->payments()->where('status', 'clear');

        return $payments;
    }

    protected function getTotalBalanceAttribute()
    {

        $payments = $this->bill()->firstOrFail()->payments()->where('status', 'received')->where('payment_mode', 'payment');

        $totalPendingPayment = $payments->sum('amount');

        return $totalPendingPayment;
    }




    /******** end mutators ********/

    /* navigation */
    public function termination()
    {
        return $this->hasOne(ContractTermination::class, 'contract_id');
    }

    public function villa()
    {
        return $this->hasOne(Villa::class, "id", "villa_id");
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class, "id", "tenant_id");
    }

    public function bill()
    {

        return $this->hasMany(ContractBill::class, 'contract_id', 'id');
    }

    /* end navigation */

    public function toComputeAmount($rate)
    {

        $totalPeriod = $this->getDiffDays();
        $totalMonth = intval($totalPeriod / 30);
        $this->amount = $rate * $totalMonth;
    }

    public function pending()
    {

        $this->setStatus("pending");

        return $this;
    }

    public function terminate($callback)
    {
        $this->setStatus("terminated");
        return $this;
    }


    public function cancel()
    {

        $this->setStatus("cancelled");

        return $this;
    }

    public function completed()
    {

        $this->setStatus("completed");

        return $this;
    }

    public function active()
    {
        $this->setStatus("active");

        return $this;
    }

    public function isActive()
    {
        return $this->hasStatusOf('active');
    }

    public function isPending()
    {
        return $this->hasStatusOf('pending');
    }

    public function isTerminated()
    {
        $this->hasStatusOf('terminated');
    }

    /****************************************
     * * Req: Check if contract is renewable by checking if no balance
     * output: True - if cancellable
     * *****************************************/
    public function canRenew()
    {
        if ($this->total_balance > 0) {
            return false;
        }
        return true;
    }

    /****************************************
     * * Req: Check if contract is cancellable by checking if no payment has been made
     * output: True - if cancellable
     * *****************************************/
    public function canCancel()
    {
        $clearPayments = $this->clear_payments->get();
        if ($clearPayments->count() > 0) {
            return false;
        }
        return true;
    }


}
