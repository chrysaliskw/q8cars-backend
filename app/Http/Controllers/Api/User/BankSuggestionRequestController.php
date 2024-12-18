<?php

namespace App\Http\Controllers\Api\User;

use Exception;
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


class BankSuggestionRequestController extends ApiBaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'civil_id' => 'required|regex:/^\d{1,30}$/',
            'email' => 'required|email',
            'bank_name' => 'required|string',
        ], [
            'civil_id.regex' => 'Civil ID must be a numeric value with a maximum of 30 digits and no decimals.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // $settings = SmtpSetting::checkSmtpConfig();
        // if ($settings) {
            $details = [
                'title' => 'Bank Suggestion Request',
                'page'  => 'emails.admin.bank.bank_submitted',
                'suggested_bank' => $request->all()
            ];
        // }
        try {
            $service = new BankSuggestionRequestService($request);
            $service->handle(BankSuggestionRequest::TYPE_BANK);

            dispatch(new SendAdminMailJob($details, $request->email));
            return $this->success(['data' => [] ], 'Bank Suggestion Request submitted successfully!', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return back()->with('failed', 'Failed! there is some issue with email provider');
        }

        // try{
        // }
        // catch (Exception $e) {
        //     logger($e);
        //     return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }
}
