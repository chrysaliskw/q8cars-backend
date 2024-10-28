<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        try{
            $service = new BankSuggestionRequestService($request);
            $service->handle();

            return $this->success(['data' => [] ], 'Bank Suggestion Request submitted successfully!', Response::HTTP_OK);
        }
        catch (Exception $e) {
            logger($e);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
