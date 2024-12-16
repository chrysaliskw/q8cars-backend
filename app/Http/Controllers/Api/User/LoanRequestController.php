<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\Bank;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
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
            'bank_id' => 'required|exists:banks,id'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // $settings = SmtpSetting::checkSmtpConfig();
        // if ($settings) {
        $bank = Bank::find($request->bank_id);

        $details = [
            'title' => 'Loan Request',
            'page'  => 'emails.admin.loan.loan_submitted',
            'loan_request' => $request->all(),
            'bank_name' => $bank ? $bank->bank_name : 'Unknown Bank',
        ];

        $existingLoanRequest = BankSuggestionRequest::where('user_id', $request->user()->id)
            ->where('status', BankSuggestionRequest::STATUS_SUBMITTED)->exists();

        if ($existingLoanRequest) {
            return $this->error('You already have a loan request submitted.', Response::HTTP_OK);
        }
        // }
        // try {

        try {
            $service = new BankSuggestionRequestService($request);
            $service->handle(BankSuggestionRequest::TYPE_LOAN);

            dispatch(new SendAdminMailJob($details, $request->email));

            return $this->success(['data' => []], 'Loan Request submitted successfully!', Response::HTTP_OK);
        } catch (Exception $e) {
            logger($e);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
