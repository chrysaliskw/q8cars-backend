<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CarDetailResource;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\FaqListResource;
use App\Models\CarVersion;
use App\Models\Faq;
use App\Models\Review;

class CarController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $car = Car::find($id);

        $data['key_features'] = $this->getKeyFeatures($car);
        $data['key_specifications'] = $this->getKeySpecifications($car);
        // $data['specification_and_features'] = $this->getAllSpecificationAndFeatures($car);
        $data['version_price_mileage['] = $this->getCarVersionAndPrice($car);
        $data['summary'] = $this->getSummary($car);
        // $data['compare_with_similar'] = $this->getComparison($car);
        $data['reviews'] = $this->getReviews($car);
        $data['faq'] = $this->getFaq($car);
        $data['news_banner'] = $this->getNews($car);
        // $data['related_news'] = $this->getRelatedNews($car);
    }

    private function getKeyFeatures(Car $car)
    {
        return [
            'air_condition' => $car->air_condition,
            'length' => $car->length. ' mm',
            'width' => $car->width. ' mm',
            'height' => $car->height. ' mm',
            'boot_space' => $car->boot_space. ' L',
            'power_windows' => $car->power_windows,
            'fuel_tank_capacity' => $car->fuel_tank_capacity. 'L',
            'seat_upholstery' => $car->seat_upholstery,
        ];
    }

    private function getKeySpecifications(Car $car)
    {
        return [
            'fuel_types' => $car->fuel_types,
            'engine_capacity' => $car->engine_capacity. ' cc',
            'power' => $car->power. 'Bph',
            'torque' => $car->torque. 'Bph',
            'drive_train' => $car->drive_train,
            'acceleration' => $car->acceleration.' sec',
            'top_speed' => $car->top_speed.' kmph',
            'seat_capacity' => $car->seat_capacity,
            'mileage' => $car->mileage. ' klmp',
        ];
    }

  

    private function getSummary(Car $car)
    {
        return [
            'why_choose' => $car->why_choose,
            'market_introduction' => $car->market_introduction,
            'engine_transmission' => $car->engine_transmission,
            'exterior' => $car->exterior,
            'interior' => $car->interior,
            'safety_features' => $car->safety_features,
            'rivals' => $car->rivals,
        ];
    }

    private function getFaq(Car $car)
    {
       $result = Faq::where('car_id', $car->id)
                    ->where('status', Faq::STATUS_ACTIVE)
                    ->orderBy('sort_order', 'asc')
                    ->limit(4)
                    ->get();

        return FaqListResource::collection($result);
    }

    private function getReviews(Car $car)
    {
        $result = Review::where('status', Review::STATUS_VERIFIED)
                    ->where('car_id', $car->id)
                    ->orderBy('sort_order', 'asc')
                    ->limit(4)
                    ->get();

        return [
            'avg_rating' => $car->avg_rating,
            'total_reviews_count' => $car->total_reviews_count,
            'rating_1_count' => $car->rating_1,
            'rating_2_count' => $car->rating_2,
            'rating_3_count' => $car->rating_3,
            'rating_4_count' => $car->rating_4,
            'rating_5_count' => $car->rating_5,
            'review' => FaqListResource::collection($result)
        ];
        
    }

    private function getCarVersionAndPrice(Car $car)
    {
        $versions = CarVersion::where('car_id', $car->id)->get();
        return CarDetailResource::collection($versions);
    }

    private function getNews(Car $car)
    {
         

    }

   
}
