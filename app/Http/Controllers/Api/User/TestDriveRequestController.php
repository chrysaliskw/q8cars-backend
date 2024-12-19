<?php

namespace App\Http\Controllers\Api\User;


use Exception;

use App\Models\TestDrive;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\SendAdminMailJob;
use App\Traits\ThrottlesLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
        $testDriveRequest = TestDrive::where('car_id', $request->car_id)->where('user_id', Auth::id())->whereIn('status', [TestDrive::STATUS_ONGOIND, TestDrive::STATUS_SUBMITTED, TestDrive::STATUS_COMPLETED])->first();
        if ($testDriveRequest) {
            if ($testDriveRequest->status == TestDrive::STATUS_COMPLETED) {
                $msg = 'You have already completed the test ride for this car';
            } else if ($testDriveRequest->status == TestDrive::STATUS_ONGOIND) {
                $msg = 'Your Test ride request already under processing';
            } else {
                $msg = 'You already have a Test ride request submitted for this car';
            }
            return $this->error(__($msg), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $service = new TestDriveRequestService($request);
            $res = $service->sendOtp();

            //ToDo SMS gateway integration job
        } catch (Exception $ex) {
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

            // $settings = SmtpSetting::checkSmtpConfig();
            // if ($settings) {
            $testDriveRequest = TestDrive::where('mobile', $request->mobile)
                ->where('user_id', Auth::id())
                ->where('status', TestDrive::STATUS_SUBMITTED)
                ->latest()
                ->first();

            if ($testDriveRequest) {
                $car = $testDriveRequest->car;
                $details = [
                    'title' => 'Test Drive Request Submitted',
                    'page' => 'emails.admin.testdrive.testdrive_submitted',
                    'first_name' => $testDriveRequest->first_name,
                    'last_name' => $testDriveRequest->last_name,
                    'car_name' => $car->model_name ?? 'Unknown Car',
                ];

                dispatch(new SendAdminMailJob($details, Auth::user()->email));
            }
            // }

            return $this->success(['data' => $res['data']], $res['msg'], Response::HTTP_OK);
        } catch (UnprocessableEntityException $ex) {
            return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // try {
        //     $this->ensureIsNotRateLimited($request);
        //     RateLimiter::hit($this->throttleKey());

        //     $service = new TestDriveRequestService($request);
        //     $res = $service->verifyOtp();
        //     if (isset($res['error'])) {
        //         return $this->error($res['error'], Response::HTTP_UNPROCESSABLE_ENTITY);
        //     }

        //     $testDriveRequest = TestDrive::where('mobile', $request->mobile)
        //     ->where('user_id', Auth::id())
        //     ->latest()
        //     ->first();

        //     if ($testDriveRequest) {
        //         $user = Auth::user();
        //         $car = $testDriveRequest->car;
        //     }

        //     return $this->success(['data' => $res['data']], $res['msg'], Response::HTTP_OK);

        // } catch (UnprocessableEntityException $ex) {
        //     return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        // } catch (Exception $ex) {
        //     logger($ex);
        //     return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
    }
}
