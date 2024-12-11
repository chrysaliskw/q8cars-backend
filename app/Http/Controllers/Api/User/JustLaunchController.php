<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;
use App\Models\CarVersion;

class JustLaunchController extends ApiBaseController
{
    public function index(Request $request)
    {
        $result = Car::where('is_just_launched', Car::JUST_LAUNCHED)
                    ->orderByRaw('COALESCE(just_launch_sort_order) ASC')
                    ->when($request->brand_id, function($query, $value){
                        $query->where('cars.brand_id', $value);
                    })
                    ->first();
        if($request->id) {
            $result = Car::find($request->id);
        }
   
        $data['id'] = $result->id;
        $data['brand_id'] = $result->brand_id;
        $data['model_name'] = $result->model_name;
        $data['ex_showroom_price'] = 'KWD ' . $result->ex_showroom_price;
        $data['on_road_price'] = 'KWD ' . $result->on_road_price;
        $data['finance_available'] = 'KWD '. $result->finance_available;
        $data['rating'] = $result->avg_rating;
        $data['image'] = file_asset('files-car', $result->image);
        $data['fuel_types'] =  $this->getFuelTypes($result->fuel_types);
        $data['transmission_types'] =  $this->getTransmissionTypes($result->fuel_types);
        $data['mileage'] =  $this->getMileages($result);
        $data['seating'] =  $this->getSeatingCapacity($result);
        $data['tank_capacity'] =  $this->getTankCapacity($result);
        $data['summary'] = $result->why_choose;
        $data['image_2'] = file_asset('files-car', $result->image_2);

        return $this->success(['data' => $data], 'Just Launch Details', Response::HTTP_OK);
    }

    public function getJustLaunchCars(Request $request)
    {
        $result = Car::where('is_just_launched', Car::JUST_LAUNCHED)
            ->when($request->brand_id, function($query, $value){
                $query->where('cars.brand_id', $value);
            })
            ->orderByRaw('COALESCE(just_launch_sort_order) ASC')
            ->limit(50)
            ->get();

        
        return CarResource::collection($result)
            ->additional([
                'message' => 'Just Lauch Cars listing',
                'status' => Response::HTTP_OK
            ]);
        
    }

    private function getTransmissionTypes($types)
    {
        $result = null;
        $type = json_decode($types, true);
        $newArray = array_combine(range(1, count($type)), array_values($type));
        foreach($newArray as $transmissionType) {
            $result[] = config('params.car.transmission_type')[$transmissionType];
        }

        return $result;
    }
    private function getFuelTypes($fuel_types)
    {
        $result = null;
        $fuel = json_decode($fuel_types, true);
        $newArray = array_combine(range(1, count($fuel)), array_values($fuel));
        foreach($newArray as $fuelType) {
            $result[] = config('params.car.fuel_type')[$fuelType];
        }

        return $result;
    }

    private function getSeatingCapacity(Car $car)
    {
        $version = CarVersion::where('car_id', $car->id)->orderBy('seat_capacity', 'asc')->pluck('seat_capacity')->toArray();
        $uniqueArray = array_unique($version);
        return $uniqueArray;
    }

    private function getMileages(Car $car)
    {
        $version = CarVersion::where('car_id', $car->id)->orderBy('mileage', 'asc')->get();
        $minVersion = $version[0]->mileage;
        $maxVersion = $version[$version->count() - 1]->mileage;
        if($minVersion == $maxVersion) {
            return $minVersion.' KM/L';
        }

        return $minVersion .'-'. $maxVersion. ' KM/L';
    }

    private function getTankCapacity(Car $car)
    {
        $version = CarVersion::where('car_id', $car->id)->orderBy('tank_capacity', 'asc')->distinct()->pluck('tank_capacity');
        $uniqueArray = json_decode($version);
        return $uniqueArray;
    }
}
