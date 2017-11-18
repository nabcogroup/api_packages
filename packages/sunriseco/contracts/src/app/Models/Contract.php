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

    protected $appends = ['full_status', 'full_contract_type', 'payable_per_month', 'full_period_start', 'full_period_end', 'total_year_month', 'total_received_payment'];

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

    

    protected function getConfigureAttribute($value)
    {
        if ($value !== null) {
            return $this->getMetaValue($value);
        } else {
            false;
        }
    }

    protected function getTotalYearMonthAttribute()
    {
        return $this->calculateTotalYearMonth($this->period_start, $this->period_end);
    }

    protected function getTotalReceivedPaymentAttribute()
    {
        $bills = $this->bill()->get();
        $amount = 0;
        if ($bills->count() > 0) {
            foreach ($bills as $bill) {
                $amount = $bill->Payments()->get()->sum('amount');
            }
        }

        return $amount;
    }

    /******** end mutators ********/

    /* navigation */
    public function contractTerminations() {
        return $this->hasOne(ContractTermination::class, 'contract_id');
    }

    public function villa() {
        return $this->hasOne(Villa::class, "id", "villa_id");
    }

    public function tenant() {
        return $this->hasOne(Tenant::class, "id", "tenant_id");
    }

    public function bill() {

        return $this->hasMany(ContractBill::class, 'contract_id', 'id');
    }

    /* end navigation */

    public function toComputeAmount($rate) {

        $totalPeriod = $this->getDiffDays();
        $totalMonth = intval($totalPeriod / 30);
        $this->amount = $rate * $totalMonth;
    }

    public function pending() {

        $this->setStatus("pending");

        return $this;
    }
    public function terminate() {
        $this->setStatus("terminated");

        return $this;

    }
    public function cancel() {

        $this->setStatus("cancelled");

        return $this;
    }
    public function completed() {

        $this->setStatus("completed");

        return $this;
    }
    public function active() {
        $this->setStatus("active");

        return $this;
    }
    public function isActive() {
        return $this->hasStatusOf('active');
    }

    public function isPending() {
        return $this->hasStatusOf('pending');
    }

    public function isTerminated() {
        $this->hasStatusOf('terminated');
    }

    public function canRenew() {

    }

}
