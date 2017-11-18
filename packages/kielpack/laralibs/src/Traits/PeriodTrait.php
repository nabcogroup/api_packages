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

    protected function globalPeriodStart($period_start) {
        if(is_null($period_start)) {
            return $this->period_start;
        }

        return $period_start;
    }

    protected function globalPeriodEnd($period_end) {
        if(is_null($period_end)) {
            return $this->period_end;
        }

        return $period_end;
    }


    public function getDiffDays($period_start = null,$period_end = null) {

        $period_start = $this->globalPeriodStart($period_start);
        $period_end = $this->globalPeriodEnd($period_end);

        return Carbon::parse($period_start)->diffInDays(Carbon::parse($period_end),true);
    }

    public function getRemainingPeriod($period_end) {

        $period_end = $this->globalPeriodEnd($period_end);

        $endPeriod = Carbon::parse($period_end);
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

        $period_start = $this->globalPeriodStart($period_start);
        $period_end = $this->globalPeriodEnd($period_end);

        $totalDays = Carbon::parse($period_start)->diffInDays(Carbon::parse($period_end));
        $totalAmountPerDays = $amount / intval($totalDays / 30);

        return $totalAmountPerDays;
    }

    public function calculateTotalYearMonth($period_start,$period_end) {

        $period_start = $this->globalPeriodStart($period_start);
        $period_end = $this->globalPeriodEnd($period_end);

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

    public function getPeriodExtensionDays($period_end = null,$extension_days =0) {

        $period_end = $this->globalPeriodEnd($period_end);

        return Carbon::parse($period_end)->addDay($extension_days);

    }


    public function getFullFormatPeriod($period_start = null,$period_end = null,$format = 'd-M-Y',$separator=' - ') {

        $period_start = $this->globalPeriodStart($period_start);
        $period_end = $this->globalPeriodEnd($period_end);

        return Carbon::parse($period_start)->format($format) . $separator . Carbon::parse($period_end)->format($format);
    }
}