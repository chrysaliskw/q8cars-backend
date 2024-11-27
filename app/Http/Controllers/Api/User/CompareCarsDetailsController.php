<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\BrandColorMapping;
use Illuminate\Support\Facades\Validator;
use App\Models\CarAdditonalSpecifications;
use App\Http\Controllers\Api\ApiBaseController;

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
            'is_common' => 'nullable|in:1',
            'is_different' => 'nullable|in:1'
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $carIds = $request->carIds;
        $isCommon = $request->is_common;
        $isDifferent = $request->is_different;
        $cars = Car::whereIn('id',$carIds)->orderByRaw('FIELD(id, ' . implode(',', $carIds) . ')')
        ->get();

        if($isCommon === '1'){
            $data = $this->getCommonCarComparison($cars);
            return $this->success(['data' => $data], 'Common Comparison Details!', Response::HTTP_OK);
        }

        if($isDifferent === '1'){
            $data = $this->getDifferentCarComparison($cars, $carIds);
            return $this->success(['data' => $data], 'Different Comparison Details!', Response::HTTP_OK);
        }

        $specifications = [];

        $basicInfo = $this->basicInfo($cars);
        $colorInfo = $this->colorInfo($cars);
        $engineInfo = $this->engineInfo($cars);
        $fuelInfo = $this->fuelInfo($cars);
        $suspensionInfo = $this->suspensionInfo($cars);
        $dimensionInfo = $this->dimensionInfo($cars);
        $comfortInfo = $this->comfortInfo($cars);
        $interiorInfo = $this->interiorInfo($cars);
        $exteriorInfo = $this->exteriorInfo($cars);
        $safetyInfo = $this->safetyInfo($cars);
        $entertainmentInfo = $this->entertainmentInfo($cars);

        if (!is_array($basicInfo)) {
            $basicInfo = [];
        }
        $specifications['basic_information'] = $basicInfo;

        if (!is_array($colorInfo)) {
            $colorInfo = [];
        }
        $specifications['colors'] = $colorInfo;

        if (!is_array($engineInfo)) {
            $engineInfo = [];
        }
        $specifications['engine_tranmission'] = $engineInfo;

        if (!is_array($fuelInfo)) {
            $fuelInfo = [];
        }
        $specifications['fuel_performance'] = $fuelInfo;

        if (!is_array($suspensionInfo)) {
            $suspensionInfo = [];
        }
        $specifications['suspension_steering'] = $suspensionInfo;

        if (!is_array($dimensionInfo)) {
            $dimensionInfo = [];
        }
        $specifications['dimension_capacity'] = $dimensionInfo;

        if (!is_array($comfortInfo)) {
            $comfortInfo = [];
        }
        $specifications['comfort_convenience'] = $comfortInfo;

        if (!is_array($interiorInfo)) {
            $interiorInfo = [];
        }
        $specifications['interior'] = $interiorInfo;

        if (!is_array($exteriorInfo)) {
            $exteriorInfo = [];
        }
        $specifications['exterior'] = $exteriorInfo;

        if (!is_array($safetyInfo)) {
            $safetyInfo = [];
        }
        $specifications['safety'] = $safetyInfo;

        if (!is_array($entertainmentInfo)) {
            $entertainmentInfo = [];
        }
        $specifications['entertainment'] = $entertainmentInfo;


        foreach ($cars as $key => $car) {
            $carSpecifications = CarAdditonalSpecifications::where('car_id', $car->id)
                ->select('specification', 'unit', 'value', 'category_id')
                ->get();

            if ($carSpecifications->isEmpty()) {
                continue;
            }

            $carSpecifications->map(function($spec) use (&$specifications, $key) {
                $category = $this->getCategoryName($spec->category_id);

                if (!isset($specifications[$category])) {
                    $specifications[$category] = [];
                }

                if (!isset($specifications[$category][$spec->specification])) {
                    $specifications[$category][$spec->specification] = [];
                }

                $value = trim($spec->value . ' ' . $spec->unit);
                if (empty($spec->unit)) {
                    $value = $spec->value;
                }

                $carKey = 'car_' . $key;
                $specifications[$category][$spec->specification][$carKey] = $value;
            });
        }

        $carCount = count($cars);


        foreach ($specifications as $category => &$specCategory) {
            foreach ($specCategory as $specification => &$specValue) {
                for ($i = 0; $i < $carCount; $i++) {
                    $carKey = 'car_' . $i;
                    if (!isset($specValue[$carKey])) {
                        $specValue[$carKey] = "";
                    }
                }
            }
        }

        $colorInfoByCar = [];

        foreach ($cars as $key => $car) {
            $carKey = 'car_' . $key;

            if (!isset($colorInfoByCar[$carKey])) {
                $colorInfoByCar[$carKey] = [];
            }

            foreach (json_decode($car->colours) as $type) {
                $map = BrandColorMapping::find($type);
                if (isset($map)) {
                    $colorInfoByCar[$carKey][$type] = $map->code;
                }
            }
        }

        $specifications['colors'] = $colorInfoByCar;

        // $data =   $this->getCarComparison($cars);
        $data = $specifications;
        return $this->success(['data' =>  $data], 'comparison Details!', Response::HTTP_OK);
    }

    private function getCategoryName($categoryId) {
        switch ($categoryId) {
            case CarAdditonalSpecifications::CATEGORY_ENGINE:
                return 'engine_tranmission';
            case CarAdditonalSpecifications::CATEGORY_FUEL:
                return 'fuel_performance';
            case CarAdditonalSpecifications::CATEGORY_SUSPENSION:
                return 'suspension_steering';
            case CarAdditonalSpecifications::CATEGORY_DIMENSION:
                return 'dimension_capacity';
            case CarAdditonalSpecifications::CATEGORY_EXTERIOR:
                return 'exterior';
            case CarAdditonalSpecifications::CATEGORY_INTERIOR:
                return 'interior';
            case CarAdditonalSpecifications::CATEGORY_SAFETY:
                return 'safety';
            case CarAdditonalSpecifications::CATEGORY_ENTERTAINMENT:
                return 'entertainment';
            case CarAdditonalSpecifications::CATEGORY_COMFORT:
                return 'comfort_convenience';
            default:
                return 'Unknown Category';
        }
    }

    private function getCommonCarComparison($cars)    {
        $specifications =  [
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

        $colorInfoByCar = [];

        foreach ($cars as $key => $car) {
            $carKey = 'car_' . $key;

            if (!isset($colorInfoByCar[$carKey])) {
                $colorInfoByCar[$carKey] = [];
            }

            foreach (json_decode($car->colours) as $type) {
                $map = BrandColorMapping::find($type);
                if (isset($map)) {
                    $colorInfoByCar[$carKey][$type] = $map->code;
                }
            }
        }

        $specifications['colors'] = $colorInfoByCar;
        return $specifications;
    }

    private function getDifferentCarComparison($cars, $carIds) {
        $specifications = [];
        $carKeyMap = [];

        foreach ($carIds as $index => $carId) {
            $carKeyMap[$carId] = 'car_' . $index;
        }

        $categories = [
            'basic_information'     => [],
            'colors'                => [],
            'engine_tranmission' => $this->engineInfo($cars),
            'fuel_performance' => $this->fuelInfo($cars),
            'suspension_steering' => $this->suspensionInfo($cars),
            'dimension_capacity' => $this->dimensionInfo($cars),
            'comfort_convenience' => $this->comfortInfo($cars),
            'interior' => $this->interiorInfo($cars),
            'exterior' => $this->exteriorInfo($cars),
            'safety' => $this->safetyInfo($cars),
            'entertainment' => $this->entertainmentInfo($cars),
        ];

        foreach ($categories as $category => $data) {
            $specifications[$category] = is_array($data) ? $data : [];
        }

        foreach ($cars as $car) {
            $carSpecifications = CarAdditonalSpecifications::where('car_id', $car->id)
                ->select('specification', 'unit', 'value', 'category_id')
                ->get();

            foreach ($carSpecifications as $spec) {
                $category = $this->getCategoryName($spec->category_id);

                if (!isset($specifications[$category])) {
                    $specifications[$category] = [];
                }

                if (!isset($specifications[$category][$spec->specification])) {
                    $specifications[$category][$spec->specification] = [];
                }

                $value = trim($spec->value . ' ' . $spec->unit);
                if (empty($spec->unit)) {
                    $value = $spec->value;
                }

                $carKey = $carKeyMap[$car->id];
                $specifications[$category][$spec->specification][$carKey] = $value;
            }
        }

        foreach ($specifications as $category => &$specCategory) {
            foreach ($specCategory as $specification => &$specValues) {
                foreach ($carKeyMap as $carKey) {
                    if (!isset($specValues[$carKey])) {
                        $specValues[$carKey] = "";
                    }
                }
            }
        }

        foreach ($specifications as $category => &$specCategory) {
            foreach ($specCategory as $specification => &$specValues) {
                $values = array_values($specValues);

                $nonEmptyValues = array_filter($values, fn($val) => !empty($val));

                if (count($nonEmptyValues) > 1) {
                    unset($specCategory[$specification]);
                }
            }
        }

        return $specifications;
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
