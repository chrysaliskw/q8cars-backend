<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BankSuggestionRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Api\User\Bank\BankSuggestionRequestService;

class LoanRequestController extends ApiBaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact_number' => 'required|regex:/^[\d]*$/',
            'email' => 'required|email',
            'bank_id' => 'nullable|exists:banks,id'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $service = new BankSuggestionRequestService($request);
        $service->handle(BankSuggestionRequest::TYPE_LOAN);

        return $this->success(['data' => [] ], 'Loan Request submitted successfully!', Response::HTTP_OK);
        try {
        } catch (Exception $e) {
            logger($e);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
