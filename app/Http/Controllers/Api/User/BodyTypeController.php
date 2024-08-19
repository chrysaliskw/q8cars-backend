<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\BodyType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BodyTypeResource;
   
class BodyTypeController extends ApiBaseController
{
    
public function __invoke(Request $request)
    {
        $bodyTypes = null;
        if($request->with_icon == 1) {
            $bodyTypes = BodyType::active()->select(['id', 'name', 'icon'])->get();
        }else {
            $bodyTypes = BodyType::active()->select(['id', 'name'])->get();
        }
        
        return $this->success([
            'data' => $bodyTypes,
            'common_data' => ['base_img_url' => file_asset('files-body_type')]
        ], 'Body Type listing', Response::HTTP_OK);
    }
}