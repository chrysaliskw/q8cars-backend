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
            'bank_id' => $this->id,
            'bank_name' => $this->bank_name,
            'max_loan_amount' => 'KWD ' . '8000', //hardcoded value
            'max_emi' => 'KWD ' . '5000', //hardcoded value
            'total_interest_payable' => $this->bank_name . ', 15%' //hardcoded value
        ];
    }
}
