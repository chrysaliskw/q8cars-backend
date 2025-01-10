<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ThrottlesLogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ApiBaseController;
use App\Exceptions\UnprocessableEntityException;
use App\Rules\RegexAlphaNumSpace;
use App\Jobs\SendSmsJob;

class AuthController extends ApiBaseController
{
    use ThrottlesLogin;

    /**
     * Login cum Registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'regex:/^[\d]*$/', 'min:7', 'max:12'],
        ], 
        [   'mobile.min' => 'The mobile must be at least 7 digits.',
            'mobile.max' => 'The mobile may not be greater than 12 digits.',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try
        {
            $this->ensureIsNotRateLimited($request);
            RateLimiter::hit($this->throttleKey());

            $user = User::where('mobile', '+965'.$request->mobile)->first();
            if (empty($user)) {
                if(User::where('mobile', $request->mobile)->withTrashed()->first()) {
                    return $this->error('Your account has been deactivated', Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                $user = $this->registerUser($request);
            }
            if($user->status == User::STATUS_INACTIVE) {
                return $this->error('Your account is not activated', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
           
            $user->otp_expiry = date('Y-m-d H:i:s', strtotime("+ 10 min"));
            $user->otp = generate_otp();
            $user->saveOrFail();


            // ToDo SMS Integration job
            $msg = 'Welcome to Q8Cars! Use this OTP to verify: '. $user->otp.' ';
            SendSmsJob::dispatch(  $request->phone_code . $request->mobile, $msg);
           
        }
        catch (UnprocessableEntityException $ex) {
            return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->success(['data' => []], 'OTP sent successfully!', Response::HTTP_OK);
    }
    
    /**
     * Registration
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User 
     */
    private function registerUser(Request $request)
    {
        $user = new User();
        $user->phone_code = '+'. 965;   // For Kuwait
        $user->country_id = 1;
        $user->mobile = $user->phone_code. $request->mobile;
        $user->status = User::STATUS_ACTIVE;
        $user->saveOrFail();

        return $user;
    }
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:' . User::class . ',id'],
            'device_name' => ['required', 'string', 'max:200', new RegexAlphaNumSpace],
        ]);
        

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::find($request->user_id); // Get the authenticated user

        // Optional: Clear old tokens if necessary
        $user->clearMobileSessions();

        // Generate a new token
        $newToken = $user->createToken($request->device_name)->plainTextToken;
        return $this->success(['data' => [
            'access_token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 60 : null,
        ]], 'Token refreshed successfully!', Response::HTTP_OK); 
    }
   
}
