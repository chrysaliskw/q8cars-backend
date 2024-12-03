<?php

namespace App\Services\Api\User\Bank;

use App\Models\Bank;
use Illuminate\Http\Request;

class LoanService
{
        // protected $base_gross_income;
        // protected $eligible_emi_percentage;
        // protected $base_other_emi;
        // protected $base_interest_rate;
        // protected $loanTenureYears;

        protected $base_gross_income;
        protected $eligible_emi_percentage;
        protected $base_other_emi;
        protected $base_interest_rate;
        protected $loanTenureYears;
        protected $totalInterestPayable;
        protected $totalAmountPayable;
        protected $bank_name;

        public function __construct(Request $request, $bankId)
        {
            $this->base_gross_income = $request->input('base_gross_income');
            $this->base_other_emi = $request->input('base_other_emi');
            $this->base_interest_rate = $request->input('base_interest_rate');
            $this->loanTenureYears = $request->input('loanTenureYears');
            $this->totalInterestPayable = 0;
            $this->totalAmountPayable = 0;

            $bank = Bank::find($bankId);
            if (!$bank) {
                throw new \Exception("Bank not found.");
            }
            $this->bank_name = $bank->bank_name;

            $this->eligible_emi_percentage = $bank->eligible_emi_percentage;
        }

        public function calculateEligibleEmi()
        {
            $maxEmi = ($this->base_gross_income * $this->eligible_emi_percentage) / 100;
            $eligibleEmi = $maxEmi - $this->base_other_emi;

            return max($eligibleEmi, 0);
        }

        public function calculateMaxLoan($eligibleEmi)
        {
            $monthlyRate = $this->base_interest_rate / (12 * 100);
            $totalMonths = $this->loanTenureYears * 12;

            // EMI formula reversed (P = EMI * ((1 + r)^n - 1) / (r * (1 + r)^n))
            $maxLoanAmount = $eligibleEmi * ((pow(1 + $monthlyRate, $totalMonths) - 1) / ($monthlyRate * pow(1 + $monthlyRate, $totalMonths)));

            return $maxLoanAmount;
        }

        public function calculateLoanEligibility()
        {
            $eligibleEmi = $this->calculateEligibleEmi();

            if ($eligibleEmi <= 0) {
                return [
                    'bank_name' => $this->bank_name,
                    'maxLoanAmount' => 0,
                    'eligibleEmi' => 0,
                    'eligibility' => 'Not eligible for loan due to existing EMI',
                    'interestRate' => ($this->base_interest_rate) . '%'
                ];
            }

            $maxLoanAmount = $this->calculateMaxLoan($eligibleEmi);

            return [
                'bank_name' => $this->bank_name,
                'maxLoanAmount' => $maxLoanAmount,
                'eligibleEmi' => $eligibleEmi,
                'eligibility' => 'Eligible for loan',
                'interestRate' => $this->base_interest_rate . '%'
            ];
        }

        public function calculateEmiSchedule()
        {
            $eligibleEmi = $this->calculateEligibleEmi();

            if ($eligibleEmi <= 0) {
                return [
                    'maxLoanAmount' => currency_formatter(0),
                    'eligibleEmi' => 0,
                    'eligibility' => 'Not eligible for loan due to existing EMI.'
                ];
            }

            $maxLoanAmount = $this->calculateMaxLoan($eligibleEmi);

            if ($this->base_gross_income <= 0 || $this->loanTenureYears <= 0) {
                return ['error' => 'base_gross_income and loan tenure must be greater than zero.'];
            }

            $monthlyInterestRate = $this->base_interest_rate / (12 * 100);
            $totalMonths = $this->loanTenureYears * 12;

            // Check if monthly interest rate is zero
            if ($monthlyInterestRate == 0) {
                $emi = round($maxLoanAmount / $totalMonths, 2);
            } else {
                $emi = ($maxLoanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalMonths)) /
                    (pow(1 + $monthlyInterestRate, $totalMonths) - 1);
                $emi = round($emi, 2);
            }

            // Initialize Variables
            $outstandingBalance = $maxLoanAmount;

            // Array to hold yearly data
            $yearlySchedule = [];

            // Loop through each month
            for ($month = 1; $month <= $totalMonths; $month++) {
                // Calculate interest for the current month
                $interestPayment = round($outstandingBalance * $monthlyInterestRate, 2);

                // Calculate base_gross_income payment for the current month
                $base_gross_incomePayment = round($emi - $interestPayment, 2);

                // Update outstanding balance
                $outstandingBalance = round($outstandingBalance - $base_gross_incomePayment, 2);
                if ($outstandingBalance < 0) {
                    $base_gross_incomePayment += $outstandingBalance;
                    $outstandingBalance = 0;
            }

            // Aggregate yearly data
            $currentYear = ceil($month / 12);

            if (!isset($yearlySchedule[$currentYear])) {
                $yearlySchedule[$currentYear] = [
                    'year' => $currentYear,
                    'months' => $currentYear * 12,
                    'base_gross_income' => 0,
                    'interest' => 0,
                    'balance' => 0,
                    'totalEmiThisYear' => 0,
                    'interestInCash' => 0,
                ];
            }

            $yearlySchedule[$currentYear]['totalEmiThisYear'] += $emi;
            $yearlySchedule[$currentYear]['base_gross_income'] += $base_gross_incomePayment;
            $yearlySchedule[$currentYear]['interestInCash'] += $interestPayment;
            $yearlySchedule[$currentYear]['interest'] = $this->calculateInterestPercentage($yearlySchedule[$currentYear]['interestInCash'], $yearlySchedule[$currentYear]['totalEmiThisYear']).'%';

            // If it's the last month of the year or the last payment, record the ending balance
            if ($month % 12 == 0 || $month == $totalMonths) {
                $this->totalInterestPayable += $yearlySchedule[$currentYear]['interestInCash'];
                $yearlySchedule[$currentYear]['balance'] = currency_formatter($outstandingBalance);
                $yearlySchedule[$currentYear]['totalEmiThisYear'] = currency_formatter($yearlySchedule[$currentYear]['totalEmiThisYear']);
                $yearlySchedule[$currentYear]['base_gross_income'] = currency_formatter($yearlySchedule[$currentYear]['base_gross_income']);
                $yearlySchedule[$currentYear]['interestInCash'] = currency_formatter($yearlySchedule[$currentYear]['interestInCash']);

            }

            // If loan is fully paid, exit the loop
            if ($outstandingBalance <= 0) {
                break;
            }
        }

        $result = [
            'emi' => currency_formatter($emi),
            'year' => $this->loanTenureYears,
            'maxLoanAmount' => currency_formatter($maxLoanAmount, 2),
            'base_gross_income' => currency_formatter($this->base_gross_income),
            'totalInterestPayable' => currency_formatter($this->totalInterestPayable),
            'totalAmountPayable' => currency_formatter($maxLoanAmount + $this->totalInterestPayable),
            'base_interest_rate' => $this->base_interest_rate . '%',
            'schedule' => array_values($yearlySchedule),
        ];

        return $result;

            // $result['emi'] = currency_formatter($emi);
            // $result['year'] = $this->loanTenureYears;
            // $result['base_gross_income'] = currency_formatter($this->base_gross_income);
            // $result['totalInterestPayable'] = currency_formatter($this->totalInterestPayable);
            // $result['totalAmountPayable'] = currency_formatter($this->base_gross_income + $this->totalInterestPayable);
            // $result['base_interest_rate'] = $this->base_interest_rate . '%';

            // foreach($yearlySchedule as $year => $value) {
            //     $result['schedule'][] = $value;
            // }

            // return $result;


            // // Final result array
            // return [
            //     'maxLoanAmount' => round($maxLoanAmount, 2),
            //     'eligibleEmi' => round($eligibleEmi, 2),
            //     'totalInterestPayable' => round($this->totalInterestPayable, 2),
            //     'totalAmountPayable' => round($maxLoanAmount + $this->totalInterestPayable, 2),
            //     'eligibility' => 'Eligible for loan.',
            //     'schedule' => $yearlySchedule
            // ];
        }

        // Function to calculate interest percentage
        public function calculateInterestPercentage($interest, $emi) {
            if ($emi == 0) return 0;
            return round(($interest / $emi) * 100, 2);
        }

}
