<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\NewsResource;
use App\Models\News;

class PopularCarController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data['popular_cars'] = $this->getPopularCars();
        $data['similar_cars'] = $this->getSimilarCras($request);
        $data['related_news'] = $this->relatedNews();
        return $this->success(['data' => $data], 'Popular Cars List with News and Similar cars', Response::HTTP_OK);
    }
    private function getPopularCars()
    {
        $result = Car::active()->orderBy('view_count', 'desc')->limit(10)->get();
        return CarResource::collection($result);
    }
    private function getSimilarCras(Request $request)
    {
        $cars = Car::active()
            ->when($request->brand_id, function ($query) use ($request) {
                $query->where('brand_id', $request->brand_id);
            })
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->groupBy('brand_id')
            ->get(); 
        return CarResource::collection($cars); 
    }
    private function relatedNews()
    {
        $carIds = Car::active()
            ->orderBy('view_count', 'desc')
            ->limit(10)
            ->pluck('id'); 

        $news = News::whereIn('car_id',$carIds)->active()->limit(5)->get();
        return NewsResource::collection($news);
    }
}