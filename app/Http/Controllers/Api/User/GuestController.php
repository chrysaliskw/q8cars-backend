<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ThrottlesLogin;
use App\Rules\RegexAlphaNumSpace;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Exceptions\UnprocessableEntityException;
use App\Http\Controllers\Api\ApiBaseController;

class GuestController extends ApiBaseController
{
    use ThrottlesLogin;

    /**
     * Guest user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'device_name' => ['required', 'string', 'max:200', new RegexAlphaNumSpace]
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $this->ensureIsNotRateLimited($request);
            RateLimiter::hit($this->throttleKey());

            $user = User::guestUser();
            
            if (empty($user)) {
                return $this->error(__('app.error'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $res['is_guest'] = true;
            $res['user'] = 'guest';
            $res['access_token'] = $user->createToken($request->device_name)->plainTextToken;
        }
        catch (UnprocessableEntityException $ex) {
        
            return $this->error($ex->getMessage(), Response::HTTP_TOO_MANY_REQUESTS);
        }
        catch (Exception $ex) {
            logger($ex);
            throw($ex);
          //  return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        return $this->success([
            'data' => $res
        ], 'Guest logged in successfully!', Response::HTTP_OK);
    }
}
