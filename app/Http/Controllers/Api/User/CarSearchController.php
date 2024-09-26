<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Services\Api\User\Car\SearchService;
use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Car;

class CarSearchController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $result = null;
// $query = Car::active();
// if($request->brand_id){
//     $query = $query->where('brand_id',$request->brand_id);
// }
// // if($request->body_type_id){
// //     $query = $query->where('brand_id',$request->brand_id);
// // }
// // if($request->brand_id){
// //     $query = $query->where('brand_id',$request->brand_id);
// // }
// $result = $query->get();
// dd($result);

        $result = (new SearchService($request))->handle();

        return CarResource::collection($result)
            ->additional([
                'message' => 'Cars search listing',
                'status' => Response::HTTP_OK
            ]);
    }
}