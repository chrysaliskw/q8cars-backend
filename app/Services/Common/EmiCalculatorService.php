<?php

namespace App\Services\Common;

use Exception;
use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmiCalculatorService
{
    protected $principal;
    protected $annualInterestRate;
    protected $loanTenureYears;
    protected $totalInterestPayable;
    protected $totalAmountPayable;
    protected $request;
    protected $onRoadPrice;

    public function __construct(Request $request)
    {
        $this->request = $request;
        // Loan Parameters
        $this->principal = $this->request->principal;// Principal loan amount (KWD)
        $this->annualInterestRate = $this->request->annualInterestRate;// Annual interest rate (%)
        $this->loanTenureYears = $this->request->loanTenureYears;// Loan tenure in years
        $this->totalInterestPayable = 0;
        $this->totalAmountPayable = 0;
        $this->onRoadPrice = $this->request->on_road_price;
    }

    public function handle()
    {

        // // Calculations
        // $monthlyInterestRate = $this->annualInterestRate / (12 * 100); // Monthly interest rate in decimal
        // $totalMonths = $this->loanTenureYears * 12; // Total number of monthly installments

        // // EMI Calculation using the formula:
        // // EMI = [P * r * (1 + r)^n] / [(1 + r)^n - 1]
        // $emi = ($this->principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalMonths)) /
        //     (pow(1 + $monthlyInterestRate, $totalMonths) - 1);
        // $emi = round($emi, 2); // Rounding off to 2 decimal places

        if ($this->principal <= 0 || $this->loanTenureYears <= 0) {
            return ['error' => 'Principal and loan tenure must be greater than zero.'];
        }

        $monthlyInterestRate = $this->annualInterestRate / (12 * 100);
        $totalMonths = $this->loanTenureYears * 12;

        // Check if monthly interest rate is zero
        if ($monthlyInterestRate == 0) {
            $emi = round($this->principal / $totalMonths, 2);
        } else {
            $emi = ($this->principal * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalMonths)) /
                   (pow(1 + $monthlyInterestRate, $totalMonths) - 1);
            $emi = round($emi, 2);
        }

        // Initialize Variables
        $outstandingBalance = $this->principal;

        // Array to hold yearly data
        $yearlySchedule = [];

        // Loop through each month
        for ($month = 1; $month <= $totalMonths; $month++) {
            // Calculate interest for the current month
            $interestPayment = round($outstandingBalance * $monthlyInterestRate, 2);

            // Calculate principal payment for the current month
            $principalPayment = round($emi - $interestPayment, 2);

            // Update outstanding balance
            $outstandingBalance = round($outstandingBalance - $principalPayment, 2);
            if ($outstandingBalance < 0) {
                $principalPayment += $outstandingBalance;
                $outstandingBalance = 0;
            }

            // Aggregate yearly data
            $currentYear = ceil($month / 12);

            if (!isset($yearlySchedule[$currentYear])) {
                $yearlySchedule[$currentYear] = [
                    'year' => $currentYear,
                    'months' => $currentYear * 12,
                    'principal' => 0,
                    'interest' => 0,
                    'balance' => 0,
                    'totalEmiThisYear' => 0,
                    'interestInCash' => 0,
                ];
            }

            $yearlySchedule[$currentYear]['totalEmiThisYear'] += $emi;
            $yearlySchedule[$currentYear]['principal'] += $principalPayment;
            $yearlySchedule[$currentYear]['interestInCash'] += $interestPayment;
            $yearlySchedule[$currentYear]['interest'] = $this->calculateInterestPercentage($yearlySchedule[$currentYear]['interestInCash'], $yearlySchedule[$currentYear]['totalEmiThisYear']).'%';

            // If it's the last month of the year or the last payment, record the ending balance
            if ($month % 12 == 0 || $month == $totalMonths) {
                $this->totalInterestPayable += $yearlySchedule[$currentYear]['interestInCash'];
                $yearlySchedule[$currentYear]['balance'] = currency_formatter($outstandingBalance);
                $yearlySchedule[$currentYear]['totalEmiThisYear'] = currency_formatter($yearlySchedule[$currentYear]['totalEmiThisYear']);
                $yearlySchedule[$currentYear]['principal'] = currency_formatter($yearlySchedule[$currentYear]['principal']);
                $yearlySchedule[$currentYear]['interestInCash'] = currency_formatter($yearlySchedule[$currentYear]['interestInCash']);

            }

            // If loan is fully paid, exit the loop
            if ($outstandingBalance <= 0) {
                break;
            }
        }
        $car = Car::find($this->request->car_id);
        $carVersion = $this->request->car_version_id ? CarVersion::find($this->request->car_version_id) : $car->carSpec;

        $result['car_id'] = $this->request->car_id;
        $result['car_model_name'] = $car->model_name;
        $result['car_version_id'] = $this->request->car_version_id  ?? $car->carSpec->id;
        if (isset($result['car_varient_name'])) {
            $result['car_varient_name'] = (CarVersion::find($this->request->car_version_id))->varient_name;
        }
        $result['emi'] = currency_formatter($emi);
        $result['year'] = $this->loanTenureYears;
        $result['principal'] = currency_formatter($this->principal);
        $result['totalInterestPayable'] = currency_formatter($this->totalInterestPayable);
        $result['totalAmountPayable'] = currency_formatter($this->principal + $this->totalInterestPayable);
        $result['annualInterestRate'] = $this->annualInterestRate . '%';
        $result['onRoadPrice'] = $carVersion ? currency_formatter($carVersion->on_road_price) : null;

        foreach($yearlySchedule as $year => $value) {
            $result['schedule'][] = $value;
        }

        $totalAmountPayable = $this->principal + $this->totalInterestPayable;

        $principalPercentage = round(($this->principal / $totalAmountPayable) * 100, 2);
        $totalInterestPercentage = round(($this->totalInterestPayable / $totalAmountPayable) * 100, 2);

        $result['principalPercentage'] = $principalPercentage . '%';
        $result['totalInterestPercentage'] = $totalInterestPercentage . '%';

        return $result;
    }

    // Function to calculate interest percentage
    public function calculateInterestPercentage($interest, $emi) {
        if ($emi == 0) return 0;
        return round(($interest / $emi) * 100, 2);
    }
}
