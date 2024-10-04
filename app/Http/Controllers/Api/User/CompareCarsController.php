<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\OfferResource;
use App\Models\Car;
use App\Models\Offer;
use App\Http\Resources\OfferDetailsResource;
use App\Models\CarComparisonList;
use App\Models\CarVersion;
use App\Models\BrandColorMapping;

class CompareCarsController extends ApiBaseController
{
    
    public function index(Request $request)
    {
        // $data['popular_offers']= $this->getPopularOffers($request);
        // $data['popular_brands'] = $this->getPopularBrandOffers($request);
        // $data['recent_offers'] = $this->getRecentOffers($request);
        // $data['suggested_offers'] = $this->getSuggestedOffers($request);
        // return $this->success(['data' => $data], 'Offer listing!', Response::HTTP_OK);
    }

    public function show($id,Request $request)
    {
       
        $comparison = CarComparisonList::find($id); 
        $carversion1 = $comparison->car_version_1_id ? $comparison->carVersion1 : $comparison->car1->carSpec;
        $carversion2 = $comparison->car_version_2_id ? $comparison->carVersion2 : $comparison->car2->carSpec;
        $data =$this->getCarComparison($carversion1, $carversion2);
        return $this->success(['data' =>  $data], 'comparison Details!', Response::HTTP_OK);
    }

    private function getCarComparison($carVersion1, $carversion2)
    {
      return [
        'basic_information'     => $this->basicInfo($carVersion1,$carversion2),
        'colors'                => $this->colorInfo($carVersion1,$carversion2),
        'engine_tranmission'    => $this->engineInfo($carVersion1,$carversion2),
        'fuel_performance'      => $this->fuelInfo($carVersion1,$carversion2),
        'suspension_steering'   => $this->suspensionInfo($carVersion1,$carversion2),
        'dimension_capacity'    => $this->dimensionInfo($carVersion1,$carversion2),
        'comfort_convenience'   => $this->comfortInfo($carVersion1,$carversion2),
        'interior'              => $this->interiorInfo($carVersion1,$carversion2),
        'exterior'              => $this->exteriorInfo($carVersion1,$carversion2),
        'safety'                => $this->safetyInfo($carVersion1,$carversion2),
        'entertainment'        => $this->entertainmentInfo($carVersion1,$carversion2),    
      ];
    }

    private function basicInfo($carVersion1,$carversion2)
    {
        return  [
            'brand_name' => [
                    'car_1' => $carVersion1->car->brand->name ,
                    'car_2' => $carversion2->car->brand->name,
                ],
                'on_road_price' => [
                    'car_1' => $carVersion1->on_road_price .'KWD',
                    'car_2' => $carversion2->on_road_price .'KWD',
                ],
                'user_rating' => [
                    'car_1' => $carVersion1->car->total_reviews_count,
                    'car_2' => $carversion2->car->total_reviews_count,
                ],
                'finance_available' => [
                    'car_1' => $carVersion1->car->finance_available.'KWD' ,
                    'car_2' => $carversion2->car->finance_available .'KWD',
                ],
                'insurance' => [
                    'car_1' => $carVersion1->car->insurance.'KWD' ,
                    'car_2' => $carversion2->car->insurance .'KWD',
                ],
                'service_cost' => [
                    'car_1' => $carVersion1->car->service_charge.'KWD',
                    'car_2' => $carversion2->car->service_charge.'KWD',
                ],
            ];
    }
    private function engineInfo($carVersion1,$carVersion2)
    {
        $engineData = [
            'Engine Capacity' => [
                'car_1' => $carVersion1->engine_capacity ,
                'car_2' => $carVersion2->engine_capacity ,
            ],
            'Tranmissioin Type' => [
                'car_1' => config('params.car.transmission_type')[$carVersion1->transmission_type] ,
                'car_2' => config('params.car.transmission_type')[$carVersion2->transmission_type] ,
            ],
            'Power' => [
                'car_1' => $carVersion1->power ,
                'car_2' => $carVersion2->power , 
            ],
            'Torque' => [
                'car_1' => $carVersion1->torque ,
                'car_2' => $carVersion2->torque , 
            ],
        ];
        $engineDetailsCar1 = $carVersion1->engine ?? [];
        $engineDetailsCar2 = $carVersion2->engine ?? [];

        foreach ($engineDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($engineDetailsCar2[$spec->specification]) ? $engineDetailsCar2[$spec->specification].' '. $engineDetailsCar2[$spec->specification] : 'null';
            $engineData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $engineData;
    }
    private function fuelInfo($carVersion1,$carVersion2)
    {
        $fuelData = [
            'Fuel Type' => [
                'car_1' => config('params.car.fuel_type')[$carVersion1->fuel_type] ,
                'car_2' => config('params.car.fuel_type')[$carVersion2->fuel_type] ,
            ],
            'Mileage' => [
               'car_1' => $carVersion1->mileage.' kmpl',
               'car_2' => $carVersion2->mileage.' kmpl',
            ]
        ];
        $fuelDetailsCar1 = $carVersion1->fuel ?? [];
        $fuelDetailsCar2 = $carVersion2->fuek ?? [];

        foreach ($fuelDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($fuelDetailsCar2[$spec->specification]) ? $fuelDetailsCar2[$spec->value].' '. $fuelDetailsCar2[$spec->unit] : '';
            $fuelData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $fuelData;
    }
    private function suspensionInfo($carVersion1,$carVersion2)
    {
        $suspensionData = [];
        $suspensionDetailsCar1 = $carVersion1->suspension ?? [];
        $suspensionDetailsCar2 = $carVersion2->suspension ?? [];

        foreach ($suspensionDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($suspensionDetailsCar2[$spec->specification]) ? $suspensionDetailsCar2[$spec->value].' '. $suspensionDetailsCar2[$spec->unit] : '';
            $suspensionData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $suspensionData;
    }
    private function dimensionInfo($carVersion1,$carversion2)
    {
        $dimensionData = [];
        $dimensionDetailsCar1 = $carVersion1->dimension ?? [];
        $dimensionDetailsCar2 = $carVersion2->dimension ?? [];

        foreach ($dimensionDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($dimensionDetailsCar2[$spec->specification]) ? $dimensionDetailsCar2[$spec->value].' '. $dimensionDetailsCar2[$spec->unit] : '';
            $dimensionData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $dimensionData;
    }
    private function comfortInfo($carVersion1,$carVersion2)
    {
        $comfortData = [];
        $comfortDetailsCar1 = $carVersion1->comfort ?? [];
        $comfortDetailsCar2 = $carVersion2->comfort ?? [];

        foreach ($comfortDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($comfortDetailsCar2[$spec->specification]) ? $comfortDetailsCar2[$spec->value].' '. $comfortDetailsCar2[$spec->unit] : '';
            $comfortData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $comfortData;
    }
    private function interiorInfo($carVersion1,$carVersion2)
    {
        $interiorData = [];
        $interiorDetailsCar1 = $carVersion1->interior ?? [];
        $interiorDetailsCar2 = $carVersion2->interior ?? [];

        foreach ($interiorDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($interiorDetailsCar2[$spec->specification]) ? $interiorDetailsCar2[$spec->value].' '. $interiorDetailsCar2[$spec->unit] : '';
            $interiorData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $interiorData;
    }
    private function exteriorInfo($carVersion1,$carVersion2)
    {
        $exteriorData = [];
        $exteriorDetailsCar1 = $carVersion1->exterior ?? [];
        $exteriorDetailsCar2 = $carVersion2->exterior ?? [];

        foreach ($exteriorDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($exteriorDetailsCar2[$spec->specification]) ? $exteriorDetailsCar2[$spec->value].' '. $exteriorDetailsCar2[$spec->unit] : '';
            $exteriorData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $exteriorData;
    }
    private function safetyInfo($carVersion1,$carVersion2)
    {
        $safetyData = [];
        $safetyDetailsCar1 = $carVersion1->safety ?? [];
        $safetyDetailsCar2 = $carVersion2->safety ?? [];

        foreach ($safetyDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($safetyDetailsCar2[$spec->specification]) ? $safetyDetailsCar2[$spec->value].' '. $safetyDetailsCar2[$spec->unit] : '';
            $safetyData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $safetyData;
    }
    private function entertainmentInfo($carVersion1,$carVersion2)
    {
        $entertainmentData = [];
        $entertainmentDetailsCar1 = $carVersion1->entertainment ?? [];
        $entertainmentDetailsCar2 = $carVersion2->entertainment ?? [];

        foreach ($entertainmentDetailsCar1 as $spec ) {
            $value1 = $spec->value.' '.$spec->unit;
            $value2 =isset($entertainmentDetailsCar2[$spec->specification]) ? $entertainmentDetailsCar2[$spec->value].' '. $entertainmentDetailsCar2[$spec->unit] : '';
            $entertainmentData[$spec->specification] = [
                'car_1' => $value1 ?? '',
                'car_2' => $value2 ?? '',
            ];
        }
        return $entertainmentData;
    }


    private function colorInfo($carVersion1,$carversion2)
    { 
        $colours1 = [];
        $colours2 = [];
        foreach (json_decode($carVersion1->car->colours) as $type) {
            $map=BrandColorMapping::find($type);
            if (isset($map)) {
                 $colours1[$type]= $map->code;
            }
        }
        foreach (json_decode($carversion2->car->colours) as $type) {
            $map=BrandColorMapping::find($type);
            if (isset($map)) {
                 $colours2[$type]= $map->code;
            }
        }
       return  [
            'car_1' => $colours1,
            'car_2' => $colours2
       ];

    }
}