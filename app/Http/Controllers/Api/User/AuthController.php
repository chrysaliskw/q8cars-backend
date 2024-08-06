<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use App\Jobs\SendSmsJob;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ThrottlesLogin;
use App\Models\NewsUserLanguage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\ApiBaseController;
use App\Exceptions\UnprocessableEntityException;
use App\Jobs\SetReferralCodeJob;
use Carbon\Carbon;

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
            'name' => 'required|string|max:255',
            'phone_code' => ['required', 'string', 'regex:/^[\d]*$/'],
            'mobile' => ['required', 'regex:/^[\d]*$/', 'min:7', 'max:12'],
            'referrer_code' => 'nullable|string',
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

            $user = User::where('mobile', $request->phone_code . $request->mobile)->first();
            if (empty($user)) {
                $user = $this->registerUser($request);
                // dd($user);
            }
            else {
                $user->name = $request->name;
            }
            
            $user->otp_expiry = date('Y-m-d H:i:s', strtotime("+ 10 min"));
            $user->otp = generate_otp();
            $user->saveOrFail();
            $country_code = $request->phone_code ?? "966";
            SendSmsJob::dispatchAfterResponse([
                $country_code ,
                $request->phone_code . $request->mobile => 'Mefriend, Your OTP to login: ' . $user->otp,
            ]);

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
        $user->name = $request->name;
        $user->phone_code = $request->phone_code;
        $user->mobile = $request->phone_code . $request->mobile;
        $user->referrer_code = $request->referrer_code??null;
        $user->status = User::STATUS_PENDING;
        $user->free_e_paper=User::FREE_E_PAPER_ENABLED;  //free e_paper with registration
        // $user->free_e_paper_expiry=Carbon::today()->addDays(30);
        $user->free_e_paper_expiry=Carbon::today()->addDays(60);

        $user->saveOrFail();

        $this->setUserLanguagesForNews($user->id);

        return $user;
    }

   
}
