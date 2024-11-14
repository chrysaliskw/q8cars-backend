<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\BrandColorMapping;

class CompareCarsDetailsController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
       

       $validator =   Validator::make($request->all(), [
            'carIds' => 'required|array',
            'carIds.*' => 'integer|exists:cars,id',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $carIds = $request->carIds;
        $cars = Car::whereIn('id',$carIds)->get();
        // $data =   $this->getCarComparison($cars);
        $data = [
            'basic_information'     => $this->basicInfo($cars),
            'colors'                => $this->colorInfo($cars),
            'engine_tranmission'    => $this->engineInfo($cars),
            'fuel_performance'      => $this->fuelInfo($cars),
            'suspension_steering'   => $this->suspensionInfo($cars),
            'dimension_capacity'    => $this->dimensionInfo($cars),
            'comfort_convenience'   => $this->comfortInfo($cars),
            'interior'              => $this->interiorInfo($cars),
            'exterior'              => $this->exteriorInfo($cars),
            'safety'                => $this->safetyInfo($cars),
            'entertainment'        => $this->entertainmentInfo($cars),    
        ];
        return $this->success(['data' =>  $data], 'comparison Details!', Response::HTTP_OK);
    }

    private function getCarComparison($cars)    {
      return [
        'basic information'     => $this->basicInfo($cars),
        'colors'                => $this->colorInfo($cars),
        'engine and tranmission'    => $this->engineInfo($cars),
        'fuel and performance'      => $this->fuelInfo($cars),
        'suspension and steering'   => $this->suspensionInfo($cars),
        'dimension and capacity'    => $this->dimensionInfo($cars),
        'comfort and convenience'   => $this->comfortInfo($cars),
        'interior'              => $this->interiorInfo($cars),
        'exterior'              => $this->exteriorInfo($cars),
        'safety'                => $this->safetyInfo($cars),
        'entertainment'        => $this->entertainmentInfo($cars),    
      ];
    }

    private function basicInfo($cars)
    {
        $comparisonData = [];
        foreach ($cars as $key => $car) {
            $comparisonData['brand name']["car_$key"] = $car->brand->name;
            $comparisonData['on road price']["car_$key"] = $car->on_road_price . ' KWD';
            $comparisonData['user rating']["car_$key"] = $car->total_reviews_count . ' Ratings';
            $comparisonData['finance available']["car_$key"] = $car->finance_available . ' KWD';
            $comparisonData['insurance']["car_$key"] = $car->insurance . ' KWD';
            $comparisonData['service cost']["car_$key"] = $car->service_charge . ' KWD';
        }
        return $comparisonData;
    }
    private function colorInfo($cars)
    { 
        $colours = [];
        foreach($cars as $car){
            foreach (json_decode($car->colours) as $type) {
                $map=BrandColorMapping::find($type);
                if (isset($map)) {
                    $colours[$car->id][$type]= $map->code;
                }
            }
        }
        return $colours;
    }

    private function engineInfo($cars)
    {
        $engineData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
            $engineData['engine capacity']["car_$key"] = strtolower($car->carSpec->engine_capacity) . ' cc';
            $engineData['transmission type']["car_$key"] = strtolower(config('params.car.transmission_type')[$car->carSpec->transmission_type]);
            $engineData['power']["car_$key"] = strtolower($car->carSpec->power) . ' Bph';
            $engineData['torque']["car_$key"] = strtolower($car->carSpec->torque) . ' rpm';
            if (isset($car->carSpec->engine)) {
                foreach ($car->carSpec->engine as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $engineData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
                }
            }
        }
        ksort($engineData);      
        return $this->mergeSimilarLabels($engineData, $carCount);
    }

    private function fuelInfo($cars)
    {
        $fuelData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
            $fuelData['fuel type']["car_$key"] =  strtolower(config('params.car.fuel_type')[$car->carSpec->fuel_type]);
            $fuelData['mileage']["car_$key"] = $car->carSpec->mileage.' kmpl';
            if (isset($car->carSpec->fuel)) {
                foreach ($car->carSpec->fuel as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $fuelData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
                }
            }
        }
        ksort($fuelData);
        return $this->mergeSimilarLabels($fuelData, $carCount);
    }

    private function suspensionInfo($cars)
    {
        $suspensionData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {          
             if (isset($car->carSpec->suspensionData)) {
                foreach ($car->carSpec->fuel as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $suspensionData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
                }
            }
        }
        ksort($suspensionData);
        return $this->mergeSimilarLabels($suspensionData, $carCount);
    }

    private function dimensionInfo($cars)
    {
        $dimensionData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {  
             $dimensionData['body type']["car_$key"] =  $car->carSpec->bodyType->name;
             if (isset($car->carSpec->dimension)) {
                foreach ($car->carSpec->dimension as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $dimensionData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
                }
            }
        }
        ksort($dimensionData);
        return $this->mergeSimilarLabels($dimensionData, $carCount);

    }
    private function comfortInfo($cars)
    {
        $comfortData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
             $comfortData['seat capacity']["car_$key"] =  $car->seat_capacity;
             if (isset($car->carSpec->comfort)) {
                foreach ($car->carSpec->comfort as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $comfortData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
            }
            }
        }
        ksort($comfortData);

       return $this->mergeSimilarLabels($comfortData, $carCount);

    }
    private function interiorInfo($cars)
    {
        $interiorData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
           
             if (isset($car->carSpec->interior)) {
                foreach ($car->carSpec->interior as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $interiorData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
    }
            }
        }
        ksort($interiorData);

       return $this->mergeSimilarLabels($interiorData, $carCount);

    }
    private function exteriorInfo($cars)
    {
        $exteriorData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
           
             if (isset($car->carSpec->exterior)) {
                foreach ($car->carSpec->exterior as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $exteriorData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
      }
            }
        }
        ksort($exteriorData);

       return $this->mergeSimilarLabels($exteriorData, $carCount);

    }
    private function safetyInfo($cars)
    {
        $safetyData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
            $safetyData['no of airbags']["car_$key"] =  $car->carSpec->no_of_airbags;
            $safetyData['safety ratings']["car_$key"] =  $car->carSpec->safety_ratings;
            
             if (isset($car->carSpec->safety)) {
                foreach ($car->carSpec->safety as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $safetyData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec ,$key);
                }
            }
        }
        ksort($safetyData);

       return $this->mergeSimilarLabels($safetyData, $carCount);

    }
    private function entertainmentInfo($cars)
    {
        $entertainmentData = [];
        $carCount = $cars->count();
        foreach ($cars as $key => $car) {
            if (isset($car->carSpec->entertainment)) {
                foreach ($car->carSpec->entertainment as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $entertainmentData[$specLabelLower]["car_$key"] = $this->appendValue($entertainmentData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
    
        ksort($entertainmentData); 
        return $this->mergeSimilarLabels($entertainmentData, $carCount);
    }
    
    private function appendValue(&$data, $formattedLabel, $car, $spec, $key)
    {
        if (isset($data[$formattedLabel]["car_$key"])) {
            return $data[$formattedLabel]["car_$key"] .= ' / ' . $spec->value . $spec->unit;
        }
        return $spec->value . $spec->unit;
    }
    
    private function mergeSimilarLabels(&$data, $carCount)
    {
        $keys = array_keys($data);

        // Loop through the keys to find and merge similar labels
        for ($i = 0; $i < count($keys); $i++) {
            for ($j = $i + 1; $j < count($keys); $j++) {
                similar_text($keys[$i], $keys[$j], $percentage);

                // If similarity is greater than 85%, merge the labels
                if ($percentage > 85) {
                    foreach ($data[$keys[$j]] as $car => $value) {
                        // Combine values from similar labels
                        if (isset($data[$keys[$i]][$car])) {
                            $data[$keys[$i]][$car] .= ' / ' . $value;
                        } else {
                            $data[$keys[$i]][$car] = $value;
                        }
                    }
                    unset($data[$keys[$j]]); // Remove the similar label after merging
                }
            }

            // Ensure each car has a value for the specification
            for ($k = 0; $k < $carCount; $k++) {
                $carKey = "car_$k";
                if (!isset($data[$keys[$i]][$carKey])) {
                    unset($data[$keys[$i]]); // Remove the label if a car doesn't have a value
                    break; // No need to check further if one car is missing
                }
            }
        }

        return $data;
    }
 
   
   
}