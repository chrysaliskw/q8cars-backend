<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileImageUpdateController extends ApiBaseController
{
    /**
     * Updates user profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function picture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'required|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try
        {
            $user = User::find(Auth::id());
            $request->picture->store(User::FILE_DIR);
            $user->picture = $request->picture->hashName();            
            $user->saveOrFail();
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->success([
            'data' => $user->profileResponseToApi()
        ], 'Profile Image updated successfully!', Response::HTTP_OK);

    }

}
