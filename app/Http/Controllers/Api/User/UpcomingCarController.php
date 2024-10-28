<?php

namespace App\Http\Controllers\Api\User;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Resources\NewsResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\CarResource;
use App\Models\Car;

class UpcomingCarController extends ApiBaseController
{
    public function __invoke(Request $request)
    {
        $data['upcoming-cars-below_5000'] = $this->getBelow5000Cars($request);
        $data['upcoming_cars'] = $this->getUpcomingCars($request);
        $data['related_news'] = $this->relatedNews($request);
        return $this->success(['data' => $data], 'Popular Cars List with News and Similar cars', Response::HTTP_OK);
    }
    private function getUpcomingCars(Request $request)
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
            return  CarResource::collection($cars);
        }

    private function getBelow5000Cars(Request $request)
    {
        $query = Car::leftJoin('car_versions', 'cars.id', '=', 'car_versions.car_id')
            ->where('cars.status', Car::STATUS_ACTIVE)
            ->upcoming()
            ->orderBy('view_count', 'desc')
            ->select('cars.*')
            ->distinct()
            ->where('cars.on_road_price', '<', 5000);

            $query->when($request->has('brand'), function ($query) use ($request) {
                $query->where('cars.brand_id', $request->brand);
            });
            $cars = $query->get();
            return  CarResource::collection($cars);
        }
    private function relatedNews(Request $request)
    {
        $carIds = Car::active()->upcoming()
        ->when($request->brand_id, function ($query) use ($request) {
            $query->where('brand_id', $request->brand_id);
        })
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->pluck('id'); 

        $news = News::whereIn('car_id',$carIds)->active()->limit(5)->get();
        return NewsResource::collection($news);
    }
}
