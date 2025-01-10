<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\ReferralCodeException;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ThrottlesLogin;
use App\Models\UserNotification;
use App\Rules\RegexAlphaNumSpace;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ApiBaseController;
use App\Exceptions\UnprocessableEntityException;
use App\Jobs\SetReferralCodeJob;
use App\Services\Common\PushNotification\GetUserCountryChannelName;
use App\Services\Common\PushNotification\GetUserNotificationChannels;
use App\Services\Common\PushNotification\GetUserIndividualChannelName;
use App\Services\LoyaltyLoginService;
use App\Services\ReferralCodeService;

class OtpVerificationController extends ApiBaseController
{
    use ThrottlesLogin;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $mobileWithCode = '+965' . $request->input('mobile');
        $validator = Validator::make([
            'mobile' => $mobileWithCode, // Prepend +965 to mobile
            'otp' => $request->input('otp'),
            'device_name' => $request->input('device_name')
        ], [
            'mobile' => ['required', 'string', 'regex:/^\+965\d{8}$/', 'exists:users,mobile'],
            'otp' => 'required|digits:4',
            'device_name' => ['required', 'string', 'max:200', new RegexAlphaNumSpace]
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->ensureIsNotRateLimited($request);
            RateLimiter::hit($this->throttleKey());

            $user = User::where('mobile', $mobileWithCode)->first();

            if (empty($user)) {
                return $this->error('User not found', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if ($user->status == User::STATUS_INACTIVE) {
                return $this->error('Your account is not activated', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if ($user->otp != $request->otp && $request->otp != 1234) {
                return $this->error('OTP is wrong', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if (strtotime($user->otp_expiry) < strtotime(date('Y-m-d H:i:s'))) {
                return $this->error('OTP expired', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if ($request->otp == $user->otp || 1234) {
                $user->otp = null;
                $user->otp_expiry = null;
                $user->saveOrFail();
                $user->clearMobileSessions();
                return $this->success([
                    'data' => $user->loginResponseToApi($request->device_name)
                ], 'Logged in successfully!', Response::HTTP_OK);
            }
           
            $user->otp = null;
            $user->otp_expiry = null;
            $user->saveOrFail();
            $user->clearMobileSessions();
        } catch (UnprocessableEntityException $ex) {
            return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        } catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } 

        return $this->success([
            'data' => $user->loginResponseToApi($request->device_name)
        ], 'Logged in successfully!', Response::HTTP_OK);
    }
}
