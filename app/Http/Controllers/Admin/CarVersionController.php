<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use App\Services\Admin\CarService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CarAdditonalSpecifications;
use App\DataGrids\Admin\CarVersionDataGrid;
use App\Http\Requests\Admin\CarVersionRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\BrandColorMapping;

class CarVersionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $car = Car::find(request()->query('car'));
        $grid = new CarVersionDataGrid(request()->query('car'));

        return view('admin.car.car-version.index', compact('grid', 'car'));
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
    public function show(CarVersion $carVersion)
    {
        $car = $carVersion->car;
        $carVarient = $carVersion;

        $color = json_decode($car->colours, true);
        $colorArray = array_combine(range(1, count($color)), array_values($color));
        $colors = [];
        foreach($colorArray as $c) {
            $colors[] = BrandColorMapping::find($c)->name;
        }
        return view('admin.car.car-version.show', compact('car','carVersion', 'colors', 'carVarient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarVersion $carVersion)
    {
        $carVarientName = $carVersion->varient_name;
        if($carVersion->is_car_spec == CarVersion::CAR_SPECIFICATION) {
            $carVarientName = null;
        }
        $car = $carVersion->car;
        $carVarient = $carVersion;

        $currentBrand = json_encode([
            'id' => $car->brand_id,
            'text' => $car->brand->name
        ]);

        $currentCar = json_encode([
            'id' => $carVersion->car_id,
            'text' => $carVersion->car->model_name
        ]);

        $currentBodyType = json_encode([
            'id' => $carVarient->body_type,
            'text' => $carVarient->bodyType->name
        ]);

        $currentFuel = json_encode([
            'id' => $carVarient->fuel_type,
            'text' => config('params.car.fuel_type')[$carVarient->fuel_type]
        ]);

        $currentTravel = json_encode([
            'id' => $carVarient->travel_type,
            'text' => config('params.car.travel_type')[$carVarient->travel_type]
        ]);

        $currentTransmission = json_encode([
            'id' => $carVarient->transmission_type,
            'text' => config('params.car.transmission_type')[$carVarient->transmission_type]
        ]);

        $currentColors = [];
        $selectedColorsCount = 0;
        foreach(json_decode($car->colours) as $c) {
           array_push($currentColors, $c);
           $selectedColorsCount++;
        }
        $ccount = count(config('params.colors'));
        $additionals = collect(CarAdditonalSpecifications::where('car_id', $car->id)->where('car_version_id',$carVarient->id)->get());
        for ($i = $carVarient->carAdditionalSpecifications->count() + 1; $i <= 10; $i++) {
            $additionals->push(new CarAdditonalSpecifications());
        }
        $allTransmissionTypes = config('params.car.transmission_type');
        $selectedTransmissionTypes = array_filter(
            config('params.car.transmission_type'),
            function ($key) use ($car) {
                return in_array($key, json_decode($car->transmission_type));
            },
            ARRAY_FILTER_USE_KEY
        );
        $selectedFuelTypes = array_filter(
            config('params.car.fuel_type'),
            function ($key) use ($car) {
                return in_array($key, json_decode($car->fuel_types));
            },
            ARRAY_FILTER_USE_KEY
        );
        $selectedTravelTypes = array_filter(
            config('params.car.travel_type'),
            function ($key) use ($car) {
                return in_array($key, json_decode($car->travel_type));
            },
            ARRAY_FILTER_USE_KEY
        );

        return view('admin.car.car-version.edit', compact('car','carVersion', 'carVarient',
            'currentFuel', 'currentTransmission', 'currentBodyType', 'currentBrand', 'currentCar','selectedFuelTypes',
            'currentColors', 'selectedColorsCount', 'ccount', 'carVarientName','additionals','selectedTransmissionTypes',
            'selectedTravelTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarVersionRequest $request, CarVersion $carVersion)
    {
        $rows = $request->row_count;


        $rules = [];
        for ($i = 0; $i < $rows; $i++) {
            $rules["attribute_$i"] = 'nullable|string|max:255';
            $rules["input_type_$i"] = ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])];
            $rules["section_$i"] = 'nullable|integer';
            $rules["text_value_$i"] = 'nullable|string';
            $rules["bool_value_$i"] = 'nullable|integer';
            $rules["units_$i"] = 'nullable|string';
            $rules["key_feature_$i"] = 'nullable|integer';
            $rules["key_spec_$i"] = 'nullable|integer';
            $rules["icon_$i"] = 'nullable|mimes:jpg,png,jpeg|max:2048';

        }

        // Apply the validator with dynamic rules
        $validator = Validator::make($request->all(), $rules, [], [
            'attribute_.*' => 'attribute',
            'input_type_.*' => 'input type',
            'section_.*' => 'category',
            'text_value_.*' => 'value',
            'bool_value_.*' => 'value',
            'units_.*' => 'units',
            'key_feature.*' => 'key feature',
            'key_spec.*' => 'key spec',
            'icon.*' => 'icon',
        ]);

        if($validator->fails()) {
            return back()->with('error',$validator->errors()->first())->withInput();
        }

        if ($msg = $this->categoryAttributeHasError('attribute', $validator->errors()->toArray())) {
            return back()->with('error', $msg)->withInput();
        }
        if ($msg = $this->categoryAttributeHasError('text_value', $validator->errors()->toArray())) {
            return back()->with('error', $msg)->withInput();
        }
        if ($msg = $this->categoryAttributeHasError('units', $validator->errors()->toArray())) {
            return back()->with('error', $msg)->withInput();
        }

        $attributeData = $this->setAttributes($validator->validated(),$rows);

        $data = array_merge($request->validated(), $attributeData);
        $data['row_count']= $rows;
        $data['update']= 1;
        $car = $carVersion->car;
        if($carVersion->is_car_spec == CarVersion::CAR_SPECIFICATION) {
            $carVersion = null;
        }
        // dd($data);
        $service = new CarService($data, $car, $carVersion);
        $carVersion = $service->saveToCarVersion();

        if(isset($data['attribute']))
        {
            // CarAdditonalSpecifications::where('car_id',$car->id)->where('car_version_id',$carVersion->id)->delete();
            $service->saveCategoryAttributes();
        }
        try
        {

        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.car-version.show', $carVersion)->with('success', 'Car version updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarVersion $carVersion)
    {
        $car = $carVersion->car;
        DB::beginTransaction();
        try {

            CarAdditonalSpecifications::where('car_version_id', $carVersion->id)->delete();
            $carVersion->delete();

            DB::commit();
        }catch(Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.car.show', $car)->with('success', 'Car version deleted successfully!');
    }
    private function categoryAttributeHasError($key, $errors)
    {
        if (empty($errors)) {
            return false;
        }

        $result = array_filter($errors, function ($a) use($key) {
            return preg_match("!^{$key}.!", $a);
        }, ARRAY_FILTER_USE_KEY);
        if (empty($result)) {
            return false;
        }

        return array_values($result)[0][0];
    }

    public function setAttributes($array,$rows)
    {
        // dd($array);
        $data['section'] = [];
        $data['attribute'] = [];
        $data['input_type'] = [];
        $data['text_value'] = [];
        $data['units'] = [];
        $data['attribute_id'] = [];
        $data['key_feature'] = [];
        $data['key_spec'] = [];
        $data['icon'] = [];
        $j = 0;

        for($i = 0 ; $i < $rows; $i++) {
            $data['section'][$j] = $array['section_'. $i] ??'';
            $data['attribute'][$j] = $array['attribute_'. $i];
            $data['input_type'][$j] = isset($array['input_type_'. $i])&&($array['input_type_'. $i] == 1) ? 1: 2;
            $data['text_value'][$j] = isset($array['input_type_'. $i])&&($array['input_type_'. $i] == 1)? $array['text_value_'. $i]: '';
            $data['bool_value'][$j] =  isset($array['input_type_'. $i])&&($array['input_type_'. $i] == 2) ? $array['bool_value_'.$i]: null;
            $data['units'][$j] = $array['units_'. $i] ??'';
            $data['attribute_id'][$j] = isset($array['attribute_id_'. $i]) ? $array['attribute_id_'. $i]: null;
            $data['key_feature'][$j] = isset($array['key_feature_'. $i]) ? $array['key_feature_'. $i]: 0;
            $data['key_spec'][$j] = isset($array['key_spec_'. $i]) ? $array['key_spec_'. $i]: 0;
            $data['icon'][$j] = isset($array['icon_'. $i]) ? $array['icon_'. $i]: null;
            $j++;
        }

        return $data;
    }


    /**
     * Search endpoint for select2 dropdown
     *
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        $page = $request->query('page');
        $term = $request->query('search');
        $carId = $request->query('car_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = CarVersion::where('varient_name', 'like', "%$term%")->where('is_car_spec', CarVersion::NOT_BASE_VARIENT)->active();
        if ($carId) {
            $query->where('car_id', $carId);
        }
        $cars = $query->select(['id', 'varient_name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cars;
        $response['pagination'] = ['more' => !empty($cars) ?? false];

        return $response;
    }

}
