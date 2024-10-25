<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\CarResource;
use App\Models\Car;

class UpcomingCarController extends ApiBaseController
{
    public function __invoke(Request $request)
    {
        $query = Car::leftJoin('car_versions', 'cars.id', '=', 'car_versions.car_id')
        ->where('cars.status', Car::STATUS_ACTIVE)
        ->upcoming()
        ->orderBy('view_count', 'desc')
        ->select('cars.*')
        ->distinct();  
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
        //CarResource::collection($cars);
        return $this->success(['data' =>  CarResource::collection($cars)], 'Upcoming Cars', Response::HTTP_OK);
  
    }
}
