<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 12:30 PM
 */

namespace KielPack\LaraLibs\Traits;


use Carbon\Carbon;

trait PeriodTrait
{
    public function getDiffDays() {

        return Carbon::parse($this->period_start)->diffInDays(Carbon::parse($this->period_end),true);
    }

    public function getRemainingPeriod() {

        $endPeriod = Carbon::parse($this->period_end);
        $remaining = $endPeriod->diffInDays(Carbon::now());
        
        return $remaining;

    }

    public function setDefaultPeriod(Carbon $startPeriod, $default, $extraPeriod = 0) {

        $this->period_start = $startPeriod->toDateTimeString();
        $this->period_end = $startPeriod->addMonth($default)->addDay(-1)->toDateTimeString();
    }

    public function setPeriod($periodStart, $periodEnd)
    {
        $this->period_start = $periodStart;
        $this->period_end = $periodEnd;
    }

    public function calculatePayableAmount($period_start,$period_end,$amount) {

        $totalDays = Carbon::parse($period_start)->diffInDays(Carbon::parse($period_end));
        $totalAmountPerDays = $amount / intval($totalDays / 30);

        return $totalAmountPerDays;
    }

    public function calculateTotalYearMonth($period_start,$period_end) {

        $totalDays = (Carbon::parse($period_end)->diffInDays(Carbon::parse($period_start)));
        $totalMonths = floor($totalDays / 30);
        $totalRemaining = $totalMonths % 12;

        $totalYear = ($totalMonths - $totalRemaining) / 12;
        if ($totalRemaining > 0) {
            if ($totalYear > 0)
                return $totalYear . "." . $totalRemaining . " / " . $totalMonths;
            else
                return $totalYear . "." . $totalRemaining . " / " . $totalMonths;
        }
        else {
            return $totalYear . " / " . $totalMonths;
        }
    }
}