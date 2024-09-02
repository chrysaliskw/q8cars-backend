<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Api\ApiBaseController;

class PopularCarFilterController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
      
        $query = Car::join('car_versions', 'cars.id', '=', 'car_versions.car_id')
                    ->active()
                    ->orderBy('view_count', 'desc');
                      
        if ($request->has('brand')) {   
            $query->where('cars.brand_id', $request->brand);
        }
        if ($request->has('budget')) {
            $budget = explode('-', $request->budget);  
            $query->whereBetween('cars.on_road_price', $budget);
        }
        if ($request->has('fuel_type')) {
            $fuelType = $request->input('fuel_type'); 
            $query->where('car_versions.fuel_type', (int) $fuelType);
        }      
        if ($request->has('body_type')) {
            $query->where('car_versions.body_type', (int) $request->body_type);
        }
        $cars = $query->limit(10)->get();
        CarResource::collection($cars);
        return $this->success(['data' => $cars], 'Popular Cars', Response::HTTP_OK);
    }
}