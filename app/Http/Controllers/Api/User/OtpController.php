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
        $data = $request->all();
        $data['mobile'] = '+965' . $data['mobile'];

        $validator = Validator::make($data, [
            'mobile' => ['required', 'string', 'regex:/^\+965\d+$/', 'exists:' . User::class],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $user = User::where('mobile', '+965'.$request->mobile)->first();
            if (empty($user)) {
                return $this->error('User not found', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $user->otp_expiry = date('Y-m-d H:i:s', strtotime("+ 10 min"));
            $user->otp = generate_otp();
            $user->saveOrFail();

            //ToDo SMS gateway integration job
            $msg = 'Welcome to New Sayara! Use this OTP to verify: '. $user->otp.' ';
            SendSmsJob::dispatch(  $user->phone_code . $request->mobile, $msg);
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->success(['data' => []], 'OTP sent successfully!', Response::HTTP_OK);
    }
}
