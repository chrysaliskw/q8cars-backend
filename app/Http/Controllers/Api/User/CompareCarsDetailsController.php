<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\RecentComparison;
use App\Models\BrandColorMapping;
use App\Models\FavouriteComparison;
use Illuminate\Support\Facades\Auth;
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
            'is_different' => 'nullable|in:1',
            'version_id' =>  'nullable|array',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $carIds = $request->carIds;
        if ((empty($request->version_id))) {
            for ($i = 0; $i < count($carIds); $i++) {
                for ($j = $i + 1; $j < count($carIds); $j++) {
                    // Check if the comparison already exists
                    $existingComparison = RecentComparison::where('user_id', Auth::id())
                        ->where(function ($query) use ($carIds, $i, $j) {
                            $query->where('car_1_id', $carIds[$i])
                                ->where('car_2_id', $carIds[$j]);
                        })
                        ->orWhere(function ($query) use ($carIds, $i, $j) {
                            $query->where('car_1_id', $carIds[$j])
                                ->where('car_2_id', $carIds[$i]);
                        })
                        ->exists();

                    // If no existing comparison, create a new one
                    if (!$existingComparison) {
                        $comparison = new RecentComparison();
                        $comparison->user_id = Auth::id();
                        $comparison->car_1_id = $carIds[$i];
                        $comparison->car_2_id = $carIds[$j];
                        $comparison->save();
                    }
                }
            }
        }
        $isCommon = $request->is_common;
        $isDifferent = $request->is_different;
        $cars = [];
        foreach ($carIds as $carId) {
            $cars[] = Car::find($carId);
        }
        // $cars[] = Car::
        // $cars = Car::whereIn('id', $carIds)->orderByRaw('FIELD(id, ' . implode(',', $carIds) . ')')
        //     ->get();

        if ($isCommon === '1') {
            $data = $this->getCommonCarComparison($cars, $request);
            return $this->success(['data' => $data], 'Common Comparison Details!', Response::HTTP_OK);
        }

        if ($isDifferent === '1') {
            $data = $this->getDifferentCarComparison($cars, $carIds, $request);
            return $this->success(['data' => $data], 'Different Comparison Details!', Response::HTTP_OK);
        }

        $specifications = [];

        $basicInfo = $this->basicInfo($cars, $request);
        $colorInfo = $this->colorInfo($cars);
        $engineInfo = $this->engineInfo($cars, $request);
        $fuelInfo = $this->fuelInfo($cars, $request);
        $suspensionInfo = $this->suspensionInfo($cars, $request);
        $dimensionInfo = $this->dimensionInfo($cars, $request);
        $comfortInfo = $this->comfortInfo($cars, $request);
        $interiorInfo = $this->interiorInfo($cars, $request);
        $exteriorInfo = $this->exteriorInfo($cars, $request);
        $safetyInfo = $this->safetyInfo($cars, $request);
        $entertainmentInfo = $this->entertainmentInfo($cars, $request);

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
                ->when(isset($request->version_id[$key]), function ($query) use ($request, $key) {
                    $query->where('car_version_id', $request->version_id[$key]);
                })
                ->select('specification', 'unit', 'value', 'category_id', 'car_version_id') // Include version_id in the selection
                ->get();


            if ($carSpecifications->isEmpty()) {
                continue;
            }

            $carSpecifications->map(function ($spec) use (&$specifications, $key) {
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

        $specifications = array_filter($specifications, function ($value) {
            return !empty($value) && (is_array($value) ? !empty(array_filter($value)) : true);
        });

        // $data =   $this->getCarComparison($cars);
        $data = $specifications;

        $maxCars = 4;
        $carIdsPadded = array_pad($carIds, $maxCars, null);
        sort($carIdsPadded);

        $existingFavourite = FavouriteComparison::where('user_id', Auth::id())
            ->where(function ($query) use ($carIdsPadded) {
                $query->where('car_1', $carIdsPadded[0])
                    ->where('car_2', $carIdsPadded[1])
                    ->where('car_3', $carIdsPadded[2])
                    ->where('car_4', $carIdsPadded[3]);
            })
            ->exists();

        if (!$existingFavourite) {
            FavouriteComparison::create([
                'user_id' => Auth::id(),
                'car_1' => $carIdsPadded[0],
                'car_2' => $carIdsPadded[1],
                'car_3' => $carIdsPadded[2],
                'car_4' => $carIdsPadded[3],
            ]);
        }

        return $this->success(['data' =>  $data], 'comparison Details!', Response::HTTP_OK);
    }

    private function getCategoryName($categoryId)
    {
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

    private function getCommonCarComparison($cars, Request $request)
    {
        $specifications =  [
            'basic_information'     => $this->basicInfo($cars, $request),
            'colors'                => $this->colorInfo($cars),
            'engine_tranmission'    => $this->engineInfo($cars, $request),
            'fuel_performance'      => $this->fuelInfo($cars, $request),
            'suspension_steering'   => $this->suspensionInfo($cars, $request),
            'dimension_capacity'    => $this->dimensionInfo($cars, $request),
            'comfort_convenience'   => $this->comfortInfo($cars, $request),
            'interior'              => $this->interiorInfo($cars, $request),
            'exterior'              => $this->exteriorInfo($cars, $request),
            'safety'                => $this->safetyInfo($cars, $request),
            'entertainment'        => $this->entertainmentInfo($cars, $request),
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

        $specifications = array_filter($specifications, function ($value) {
            return !empty($value) && (is_array($value) ? !empty(array_filter($value)) : true);
        });

        return $specifications;
    }

    private function getDifferentCarComparison($cars, $carIds, Request $request)
    {
        $specifications = [];
        $carKeyMap = [];
        if ($request->version_id) {
            foreach ($carIds as $index => $carId) {
                $key = $request->version_id[$index] ?? $cars[$index]->carSpec->id;

                $carKeyMap[$key] = 'car_' . $index;
            }
        } else {
            foreach ($carIds as $index => $carId) {
                $carKeyMap[$cars[$index]->carSpec->id] = 'car_' . $index;
            }
        }
        $categories = [
            // 'basic_information'     => [],
            // 'colors'                => [],
            'engine_tranmission' => $this->engineInfo($cars, $request),
            'fuel_performance' => $this->fuelInfo($cars, $request),
            'suspension_steering' => $this->suspensionInfo($cars, $request),
            'dimension_capacity' => $this->dimensionInfo($cars, $request),
            'comfort_convenience' => $this->comfortInfo($cars, $request),
            'interior' => $this->interiorInfo($cars, $request),
            'exterior' => $this->exteriorInfo($cars, $request),
            'safety' => $this->safetyInfo($cars, $request),
            'entertainment' => $this->entertainmentInfo($cars, $request),
        ];

        foreach ($categories as $category => $data) {
            $specifications[$category] = is_array($data) ? $data : [];
        }

        foreach ($cars as $key => $car) {
            $carSpecifications = CarAdditonalSpecifications::where('car_id', $car->id)
                ->when(isset($request->version_id[$key]), function ($query) use ($request, $key) {
                    $query->where('car_version_id', $request->version_id[$key]);
                }, function ($query) use ($car) {
                    $query->where('car_version_id', $car->carspec->id);
                })
                ->select('specification', 'unit', 'value', 'category_id', 'car_version_id')
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

                $carKey = $carKeyMap[$spec->car_version_id];
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

        $specifications = array_filter($specifications, function ($category) {
            return !empty($category);
        });

        return $specifications;
    }

    private function basicInfo($cars, $request)
    {
        // dd($request->all());
        $comparisonData = [];
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;
            // if ($request->version_id && isset($request->version_id[$key])) {
            //     $comparisonData['model name']["car_$key"] =  $version->name;
            // } else {
            //     $comparisonData['model name']["car_$key"] =  $car->name;
            // }
            $comparisonData['brand name']["car_$key"] =  $car->brand->name;
            //$comparisonData['on road price']["car_$key"] = $version->on_road_price . ' KWD';
            $comparisonData['user rating']["car_$key"] = $version->total_reviews_count . ' Ratings';
            //$comparisonData['finance available']["car_$key"] = $version->finance_available . ' KWD';
            // $comparisonData['insurance']["car_$key"] = $version->insurance . ' KWD';
            // $comparisonData['service cost']["car_$key"] = $version->service_charge . ' KWD';
        }
        return $comparisonData;
    }
    private function colorInfo($cars)
    {
        $colours = [];
        foreach ($cars as $car) {
            foreach (json_decode($car->colours) as $type) {
                $map = BrandColorMapping::find($type);
                if (isset($map)) {
                    $colours[$car->id][$type] = $map->code;
                }
            }
        }
        return $colours;
    }

    private function engineInfo($cars, $request)
    {
        $engineData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;
            $engineData['engine capacity']["car_$key"] = strtolower($version->engine_capacity) . ' cc';
            $engineData['transmission type']["car_$key"] = strtolower(config('params.car.transmission_type')[$version->transmission_type]);
            $engineData['power']["car_$key"] = strtolower($version->power) . ' Bph';
            $engineData['torque']["car_$key"] = strtolower($version->torque) . ' rpm';
            if (isset($version->engine)) {
                foreach ($version->engine as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $engineData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }

        ksort($engineData);
        return $this->mergeSimilarLabels($engineData, $carCount);
    }

    private function fuelInfo($cars, $request)
    {
        $fuelData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;
            $fuelData['fuel type']["car_$key"] =  strtolower(config('params.car.fuel_type')[$version->fuel_type]);
           // $fuelData['mileage']["car_$key"] = $version->mileage . ' kmpl';
            if (isset($version->fuel)) {
                foreach ($version->fuel as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $fuelData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($fuelData);
        return $this->mergeSimilarLabels($fuelData, $carCount);
    }

    private function suspensionInfo($cars, $request)
    {
        $suspensionData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;

            if (isset($version->suspensionData)) {
                foreach ($version->fuel as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $suspensionData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($suspensionData);
        return $this->mergeSimilarLabels($suspensionData, $carCount);
    }

    private function dimensionInfo($cars, $request)
    {
        $dimensionData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;

            $dimensionData['body type']["car_$key"] =  $version->bodyType->name;
            if (isset($version->dimension)) {
                foreach ($version->dimension as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $dimensionData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($dimensionData);
        return $this->mergeSimilarLabels($dimensionData, $carCount);
    }
    private function comfortInfo($cars, $request)
    {
        $comfortData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;

            $comfortData['seat capacity']["car_$key"] =  $car->seat_capacity;
            if (isset($version->comfort)) {
                foreach ($version->comfort as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $comfortData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($comfortData);

        return $this->mergeSimilarLabels($comfortData, $carCount);
    }
    private function interiorInfo($cars, $request)
    {
        $interiorData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {

            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;
            if (isset($version->interior)) {
                foreach ($version->interior as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $interiorData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($interiorData);

        return $this->mergeSimilarLabels($interiorData, $carCount);
    }
    private function exteriorInfo($cars, $request)
    {
        $exteriorData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;
            if (isset($version->exterior)) {
                foreach ($version->exterior as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $exteriorData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($exteriorData);

        return $this->mergeSimilarLabels($exteriorData, $carCount);
    }
    private function safetyInfo($cars, $request)
    {
        $safetyData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;

            $safetyData['no of airbags']["car_$key"] =  $version->no_of_airbags;
            $safetyData['safety ratings']["car_$key"] =  $version->safety_ratings;

            if (isset($version->safety)) {
                foreach ($version->safety as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $safetyData[$specLabelLower]["car_$key"] = $this->appendValue($engineData, $formattedLabel, $car, $spec, $key);
                }
            }
        }
        ksort($safetyData);

        return $this->mergeSimilarLabels($safetyData, $carCount);
    }
    private function entertainmentInfo($cars, $request)
    {
        $entertainmentData = [];
        $carCount = count($cars);
        foreach ($cars as $key => $car) {
            $version = $request->version_id && isset($request->version_id[$key]) ? CarVersion::find($request->version_id[$key]) : $car->carSpec;

            if (isset($version->entertainment)) {
                foreach ($version->entertainment as $spec) {
                    $specLabelLower = strtolower($spec->specification);
                    $formattedLabel = strtolower(preg_replace('/[\s_-]+/', '', $spec->specification));
                    $entertainmentData[$specLabelLower]["car_$key"] = $this->appendValue($entertainmentData, $formattedLabel, $car, $spec, $key);
                }
            }
        }

        ksort($entertainmentData);

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
