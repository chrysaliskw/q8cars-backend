<?php

namespace App\Http\Controllers\Api\User;


use App\Models\TestDrive;

use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ThrottlesLogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ApiBaseController;
use App\Exceptions\UnprocessableEntityException;
use App\Services\Api\User\TestDriveRequestService;

class TestDriveRequestController extends ApiBaseController
{
    use ThrottlesLogin;
    
    /**
     * Updates user profile info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'phone_code' => 'required',
            'mobile' => ['required', 'string', 'regex:/^[\d]*$/'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try
        {
            $service = new TestDriveRequestService($request);
            $res = $service->sendOtp();
          
            //ToDo SMS gateway integration job
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        return $this->success(['data' => []], 'OTP sent successfully!', Response::HTTP_OK);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'string', 'regex:/^[\d]*$/'],
            'otp' => 'required|digits:4',
        ]);;

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
       
        try {
            $this->ensureIsNotRateLimited($request);
            RateLimiter::hit($this->throttleKey());

            $service = new TestDriveRequestService($request);
            $res = $service->verifyOtp();
            if (isset($res['error'])) {
                return $this->error($res['error'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            return $this->success(['data' => $res['data']], $res['msg'], Response::HTTP_OK);

        } catch (UnprocessableEntityException $ex) {
            return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        } catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
