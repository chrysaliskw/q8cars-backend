<?php

namespace App\Services\Api\User\Bank;

use App\Models\BankSuggestionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSuggestionRequestService
{
         /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

   /**
     * Creates a new instance
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(int $type): BankSuggestionRequest
    {
        return BankSuggestionRequest::create([
            'user_id' => Auth::id(),
            'first_name' => $this->request->first_name,
            'last_name' => $this->request->last_name,
            'contact_number' =>'+965'.$this->request->contact_number,
            'civil_id' => $this->request->civil_id,
            'email' => $this->request->email,
            'bank_name' => $this->request->bank_name,
            'status' => BankSuggestionRequest::STATUS_SUBMITTED,
            'bank_id' => $this->request->bank_id,
            'type' => $type
        ]);
    }
}

