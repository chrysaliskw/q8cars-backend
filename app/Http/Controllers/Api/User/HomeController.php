<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use App\Models\News;
use App\Models\Brand;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CarVersionResource;
use App\Http\Controllers\Api\ApiBaseController;

class HomeController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data['popular_cars'] = $this->getPopularCars();
        $data['compare_cars'] = $this->getCompareCars();
        $data['trending_news'] = $this->getTrending();
        $data['recently_purchased_brands'] = $this->getBrands();
      
        return $this->success(['data' => $data], 'Home Page', Response::HTTP_OK);
    }

    private function getPopularCars()
    {
        $result = Car::active()->orderBy('view_count', 'desc')->limit(20)->get();
        return CarResource::collection($result);
    }

    private function getCompareCars()
    {
        $result = CarVersion::whereHas('car', function($query) {
                        $query->active();
                    })
                    ->orderBy('id', 'desc')->limit(20)->get();
        return CarVersionResource::collection($result);
    }

    private function getTrending()
    {
        $result = News::active()->published()->trending()->orderBy('posted_time', 'desc')->limit(3)->get();
        return NewsResource::collection($result);
    }

    private function getBrands()
    {
        $brands = Brand::active()->orderBy('is_top_brand', 'asc')->limit(50)->get();
        return BrandResource::collection($brands);
    }
}
