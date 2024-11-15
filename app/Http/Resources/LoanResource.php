<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bank_id' => $this->resource['bank_id'],
            'bank_name' => $this->resource['bank_name'],
            'max_loan_amount' => currency_formatter($this->resource['maxLoanAmount'], 2),
            'max_emi' => currency_formatter($this->resource['eligibleEmi'], 2),
            'total_interest_payable' => $this->resource['bank_name'] . ', ' . $this->resource['interestRate'] . '%',
            'eligibility' => $this->resource['eligibility'],
        ];
    }
}
