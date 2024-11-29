<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\View360Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\SubmitReviewService;
   
class View360ImageController extends ApiBaseController
{
    
    public function __invoke(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
            $images = View360Image::where('car_id',$request->car_id)->get();
            $common_data['base_url'] = file_asset('files-360_view');
          
            return $this->success(['data' => [
                'data' => $images,
                'common-data' => file_asset('files-360_view'),
            ]], '360 view images', Response::HTTP_OK);

       

     }
}