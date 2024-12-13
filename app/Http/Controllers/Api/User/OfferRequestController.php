<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\Car;
use App\Models\SmtpSetting;
use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\OfferRequestService;
use App\Http\Controllers\Api\ApiBaseController;
use App\Models\CarVersion;

class OfferRequestController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'nullable|exists:cars,id',
            'car_version_id' => 'nullable|integer',
            'mobile' => ['required', 'regex:/^[\d]*$/', 'min:7', 'max:12'],
            'phone_code' => 'required',
            'email' => 'required|email',
            'full_name' => 'required|string',
            'type' => ['required', 'integer', 'in:1,2,3'],
            'offer_id' => [
                'nullable',  //1-get offer request ,2-get on road price, 3-emi offer
                'integer',
                // function ($attribute, $value, $fail) use ($request) {
                //     if ($request->input('type') == 1 && is_null($value)) {
                //         $fail('The offer_id field is required when type is 1.');
                //     }
                // }
            ],
        ],
        [   'mobile.min' => 'The mobile must be at least 7 digits.',
            'mobile.max' => 'The mobile may not be greater than 12 digits.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // $settings = SmtpSetting::checkSmtpConfig();
        // if ($settings) {
            $car = Car::find($request->car_id);
            $carVersion = CarVersion::find($request->car_version_id);

            $details = [
                'title' => 'Offer Request',
                'page'  => 'emails.admin.offer_request.offer_request_submitted',
                'offerRequest' => $request->all(),
            ];

            if($car){
                $details['car'] = $car ? $car->model_name : 'Unknown Car';
            }

            if($carVersion){
                $details['car_version'] = $carVersion ? $carVersion->varient_name : 'Unknown Car Version';
            }
        // }
        try {
            $service = new OfferRequestService($request);
            $service->handle();

            dispatch(new SendAdminMailJob($details, $request->email));

            return $this->success(['data' => [] ], 'Offer Request submitted successfully!', Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return back()->with('failed', 'Failed! there is some issue with email provider');
        }

        // try {
        //     $service = new OfferRequestService($request);
        //     $rex = $service->handle();

        //     return $this->success(['data' => []], 'Request Submitted Successfully', Response::HTTP_OK);

        // } catch (Exception $e) {
        //    logger($e);
        //     return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

     }
}
