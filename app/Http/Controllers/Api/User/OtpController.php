<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use App\Jobs\SendSmsJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiBaseController;

class OtpController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'string', 'regex:/^[\d]*$/', 'exists:' . User::class],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $user = User::where('mobile', $request->mobile)->first();
            if (empty($user)) {
                return $this->error('User not found', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $user->otp_expiry = date('Y-m-d H:i:s', strtotime("+ 10 min"));
            $user->otp = generate_otp();
            $user->saveOrFail();
           
            //ToDo SMS gateway integration job
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $this->success(['data' => []], 'OTP sent successfully!', Response::HTTP_OK);
    }
}
