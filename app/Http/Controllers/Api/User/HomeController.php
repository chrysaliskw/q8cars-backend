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
use App\Models\CarComparisonList;

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
        $result = Car::active()->launched()->orderBy('view_count', 'desc')->limit(10)->get();
        return CarResource::collection($result);
    }

    private function getCompareCars()
    {
        $lists = CarComparisonList::where('page', CarComparisonList::HOME_PAGE)->where('status',1)->get();
        $result = null;


        $i = 0;
        foreach($lists as $list) {
            $car1 = new CarVersionResource(CarVersion::where('car_id', $list->car_1_id)->where('is_car_spec', CarVersion::CAR_SPECIFICATION)->first());
            if($list->car_version_1_id) {
                $car1 = new CarVersionResource(CarVersion::find($list->car_version_1_id));
            }

            $car2 = new CarVersionResource(CarVersion::where('car_id', $list->car_2_id)->where('is_car_spec', CarVersion::CAR_SPECIFICATION)->first());
            if($list->car_version_2_id) {
                $car2 = new CarVersionResource(CarVersion::find($list->car_version_2_id));
            }
            $result[$i]['id'] = $list->id;
            $result[$i]['car_1'] = $car1;
            $result[$i]['car_2'] = $car2;
            $i++;
        }

        return $result;
    }

    private function getTrending()
    {
        $result = News::active()->published()->trending()->orderBy('posted_time', 'desc')->get();
        return NewsResource::collection($result);
    }

    private function getBrands()
    {
        $brands = Brand::active()->orderBy('is_top_brand', 'asc')->where('is_recently_purchased', 1)->limit(50)->get();
        return BrandResource::collection($brands);
    }
}
