<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use App\Models\Faq;
use App\Models\Review;
use App\Models\CarView;
use App\Models\CarImage;
use App\Models\TestDrive;
use App\Models\CarVersion;
use App\Models\CarFavourite;
use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CarComparisonList;
use App\Services\Admin\CarService;
use Illuminate\Support\Facades\DB;
use App\DataGrids\Admin\CarDataGrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\CarAdditonalSpecifications;
use App\DataGrids\Admin\CarVersionDataGrid;
use Illuminate\Http\Exceptions\PostTooLargeException;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new CarDataGrid(request()->query());

        return view('admin.car.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('admin.car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_1' =>'nullable|string|max:255',
            'input_type_1' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_1' => 'nullable|integer',
            'text_value_1' => 'nullable|string',
            'bool_value_1' => 'nullable|integer',
            'units_1' => 'nullable|string',
         
            'attribute_2' =>'nullable|string|max:255',
            'input_type_2' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_2' => 'nullable|integer',
            'text_value_2' => 'nullable|string',
            'bool_value_2' => 'nullable|integer',
            'units_2' => 'nullable|string',
            
            'attribute_3' =>'nullable|string|max:255',
            'input_type_3' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_3' => 'nullable|integer',
            'text_value_3' => 'nullable|string',
            'bool_value_3' => 'nullable|integer',
            'units_3' => 'nullable|string',
           
            'attribute_4' =>'nullable|string|max:255',
            'input_type_4' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_4' => 'nullable|integer',
            'text_value_4' => 'nullable|string',
            'bool_value_4' => 'nullable|integer',
            'units_4' => 'nullable|string',
            
            'attribute_5' =>'nullable|string|max:255',
            'input_type_5' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_5' => 'nullable|integer',
            'text_value_5' => 'nullable|string',
            'bool_value_5' => 'nullable|integer',
            'units_5' => 'nullable|string',
            
            'attribute_6' =>'nullable|string|max:255',
            'input_type_6' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_6' => 'nullable|integer',
            'text_value_6' => 'nullable|string',
            'bool_value_6' => 'nullable|integer',
            'units_6' => 'nullable|string',
           
            'attribute_7' =>'nullable|string|max:255',
            'input_type_7' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_7' => 'nullable|integer',
            'text_value_7' => 'nullable|string',
            'bool_value_7' => 'nullable|integer',
            'units_7' => 'nullable|string',
           
            'attribute_8' =>'nullable|string|max:255',
            'input_type_8' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_8' => 'nullable|integer',
            'text_value_8' => 'nullable|string',
            'bool_value_8' => 'nullable|integer',
            'units_8' => 'nullable|string',
            
            'attribute_9' =>'nullable|string|max:255',
            'input_type_9' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_9' => 'nullable|integer',
            'text_value_9' => 'nullable|string',
            'bool_value_9' => 'nullable|integer',
            'units_9' => 'nullable|string',
            
            'attribute_0' =>'nullable|string|max:255',
            'input_type_0' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_0' => 'nullable|integer',
            'text_value_0' => 'nullable|string',
            'bool_value_0' => 'nullable|integer',
            'units_0' => 'nullable|string',
            
        ], 
        [], 
        ['attribute_.*' => 'attribute', 'input_type_.*' => 'input type', 'section_.*' => 'category', 
        'text_value_.*' => 'value', 'bool_value_.*' => 'value', 'units_.*' => 'units',]);
        
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

        $attributeData = $this->setAttributes($validator->validated());

        $data = array_merge($request->validated(), $attributeData);    
        for($i=0;$i<10;$i++){
            $data['attribute_id'][$i] = null;
        }    
       
        try 
        {
            $service = new CarService($data);
            $car = $service->handle();
        }
        catch (PostTooLargeException $ex) {
            logger($ex);
            return back()->with('error', 'File size should be within 2MB')->withInput();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.car.show', $car)->with('success', 'Car created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
       
        $fuel = json_decode($car->fuel_types, true);
        $newArray = array_combine(range(1, count($fuel)), array_values($fuel));
        $fuelTypes =[];
        foreach($newArray as $fuelType) {
            $fuelTypes[] = config('params.car.fuel_type')[$fuelType];
        }

        $transmission = json_decode($car->transmission_type, true);
        $newAtransmissionarray = array_combine(range(1, count($transmission)), array_values($transmission));
        $transmissionTypes = [];
        foreach($newAtransmissionarray as $transmissionType) {
            $transmissionTypes[] = config('params.car.transmission_type')[$transmissionType];
        }

        $color = json_decode($car->colours, true);
        $colorArray = array_combine(range(1, count($color)), array_values($color));
        $colors = [];
        foreach($colorArray as $c) {
            $colors[] = config('params.colors')[$c];
        }

        $profession = json_decode($car->professions, true);
        $professions = [];
        if($profession) {
            $professionArray = array_combine(range(1, count($profession)), array_values($profession));
            foreach($professionArray as $c) {
                $professions[] = config('params.professions')[$c];
            }
        }
    
        $carVarient = $car->carSpec;
        $carVersion = $carVarient; 
        $carVersions = CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)->get();

        return view('admin.car.show', compact('car','carVersions','fuelTypes', 'transmissionTypes', 'carVarient', 'colors', 'professions','carVersion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        $currentBrand = json_encode([
            'id' => $car->brand_id,
            'text' => $car->brand->name
        ]);

        $currentBodyType = json_encode([
            'id' => $car->carSpec->body_type,
            'text' => $car->carSpec->bodyType->name
        ]);

        $carVarient = $car->carSpec;
        $carImages = collect(CarImage::where('car_id', $car->id)->image()->whereNull('color')->get());
        for ($i = $carImages->count() + 1; $i <= Car::MAX_NUM_IMAGES; $i++) {
            $carImages->push(new CarImage());
        }
        $carVideos = collect(CarImage::where('car_id', $car->id)->video()->get());
        for ($i = $car->carVideos->count() + 1; $i <= 3; $i++) {
            $carVideos->push(new CarImage());
        }

        $additionals = collect(CarAdditonalSpecifications::where('car_id', $car->id)->where('car_version_id',$carVarient->id)->get());
        for ($i = $carVarient->carAdditionalSpecifications->count() + 1; $i <= 10; $i++) {
            $additionals->push(new CarAdditonalSpecifications());
        }

        $currentProfessions = [];
        $selectedProfessionCount = 0;
        if($car->professions) {
            foreach(json_decode($car->professions) as $p) {
                array_push($currentProfessions, $p);
                $selectedProfessionCount++;
            }
        }
        $pcount = count(config('params.professions'));

        $currentFuels = [];
        $selectedFuelCount = 0;
        foreach(json_decode($car->fuel_types) as $f) {
           array_push($currentFuels, $f);
           $selectedFuelCount++;
        }
        $fcount = count(config('params.car.fuel_type'));

        $currentTransmissions = [];
        $selectedTransmissionCount = 0;
        foreach(json_decode($car->transmission_type) as $t) {
           array_push($currentTransmissions, $t);
           $selectedTransmissionCount++;
        }
        $tcount = count(config('params.car.transmission_type'));

        $currentColors = [];
        $selectedColorsCount = 0;
        foreach(json_decode($car->colours) as $c) {
           array_push($currentColors, $c);
           $selectedColorsCount++;
        }
        $ccount = count(config('params.colors'));

        return view('admin.car.edit', compact('car', 'carVarient',
            'currentBrand', 'currentBodyType',  
            'carImages', 'carVideos',
            'pcount', 'selectedProfessionCount', 'currentProfessions', 
            'selectedFuelCount', 'currentFuels', 'fcount', 
            'tcount','selectedTransmissionCount', 'currentTransmissions',
            'ccount','selectedColorsCount', 'currentColors',
            'additionals',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, Car $car)
    {
        $validator = Validator::make($request->all(), [
            'attribute_1' =>'nullable|string|max:255',
            'input_type_1' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_1' => 'nullable|integer',
            'text_value_1' => 'nullable|string',
            'bool_value_1' => 'nullable|integer',
            'units_1' => 'nullable|string',
            'attribute_id_1' => 'nullable',

            'attribute_2' =>'nullable|string|max:255',
            'input_type_2' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_2' => 'nullable|integer',
            'text_value_2' => 'nullable|string',
            'bool_value_2' => 'nullable|integer',
            'units_2' => 'nullable|string',
            'attribute_id_2' => 'nullable',

            'attribute_3' =>'nullable|string|max:255',
            'input_type_3' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_3' => 'nullable|integer',
            'text_value_3' => 'nullable|string',
            'bool_value_3' => 'nullable|integer',
            'units_3' => 'nullable|string',
            'attribute_id_3' => 'nullable',

            'attribute_4' =>'nullable|string|max:255',
            'input_type_4' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_4' => 'nullable|integer',
            'text_value_4' => 'nullable|string',
            'bool_value_4' => 'nullable|integer',
            'units_4' => 'nullable|string',
            'attribute_id_4' => 'nullable',

            'attribute_5' =>'nullable|string|max:255',
            'input_type_5' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_5' => 'nullable|integer',
            'text_value_5' => 'nullable|string',
            'bool_value_5' => 'nullable|integer',
            'units_5' => 'nullable|string',
            'attribute_id_5' => 'nullable',

            'attribute_6' =>'nullable|string|max:255',
            'input_type_6' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_6' => 'nullable|integer',
            'text_value_6' => 'nullable|string',
            'bool_value_6' => 'nullable|integer',
            'units_6' => 'nullable|string',
            'attribute_id_6' => 'nullable',

            'attribute_7' =>'nullable|string|max:255',
            'input_type_7' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_7' => 'nullable|integer',
            'text_value_7' => 'nullable|string',
            'bool_value_7' => 'nullable|integer',
            'units_7' => 'nullable|string',
            'attribute_id_7' => 'nullable',

            'attribute_8' =>'nullable|string|max:255',
            'input_type_8' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_8' => 'nullable|integer',
            'text_value_8' => 'nullable|string',
            'bool_value_8' => 'nullable|integer',
            'units_8' => 'nullable|string',
            'attribute_id_8' => 'nullable',

            'attribute_9' =>'nullable|string|max:255',
            'input_type_9' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_9' => 'nullable|integer',
            'text_value_9' => 'nullable|string',
            'bool_value_9' => 'nullable|integer',
            'units_9' => 'nullable|string',
            'attribute_id_9' => 'nullable',

            'attribute_0' =>'nullable|string|max:255',
            'input_type_0' => ['nullable', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])],
            'section_0' => 'nullable|integer',
            'text_value_0' => 'nullable|string',
            'bool_value_0' => 'nullable|integer',
            'units_0' => 'nullable|string',
            'attribute_id_0' => 'nullable',
        ], 
        [], 
        ['attribute_.*' => 'attribute', 'input_type_.*' => 'input type', 'section_.*' => 'category', 
        'text_value_.*' => 'value', 'bool_value_.*' => 'value', 'units_.*' => 'units',]);
        
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

        $attributeData = $this->setAttributes($validator->validated());

        $data = array_merge($request->validated(), $attributeData); 
// dd($data);
        // for($i=0;$i<10;$i++){
        //     $data['attribute_id'][$i] = $this->setAttributeIds($request);
        // } 
        $carVarient = $car->carSpec;
        try 
        {
            $service = new CarService($data, $car,$carVarient);
            $car = $service->handle(); 
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.car.show', $car)->with('success', 'Car updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if(CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)->count()) {
            return back()->with('error', 'Cannot delete car, versions exists')->withInput();
        }

        DB::beginTransaction();
        try {

            $car->carImages()->delete();
            $car->news()->delete();
            Faq::where('car_id', $car->id)->delete();
            CarFavourite::where('car_id', $car->id)->delete();
            Review::where('car_id', $car->id)->delete();
            TestDrive::where('car_id', $car->id)->delete();
            CarView::where('car_id', $car->id)->delete();
            CarAdditonalSpecifications::where('car_id', $car->id)->delete();
            CarComparisonList::where('car_1_id', $car->id)->orWhere('car_2_id', $car->id)->delete();
            OfferRequest::where('car_id', $car->id)->delete();
           
            $car->delete();

            DB::commit();
        }catch(Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.car.index')->with('success', 'Car deleted successfully!');
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
        $brandId = $request->query('brand_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = Car::where('model_name', 'like', "%$term%")->active();
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }
        $cars = $query->select(['id', 'name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cars;
        $response['pagination'] = ['more' => !empty($cars) ?? false];
        
        return $response;
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

    public function setAttributes($array)
    {
        // dd($array);
        $data['section'] = [];
        $data['attribute'] = [];
        $data['input_type'] = [];
        $data['text_value'] = [];
        $data['units'] = [];
        $data['attribute_id'] = [];
        $j = 0;

        for($i = 0 ; $i < 10; $i++) {
            $data['section'][$j] = $array['section_'. $i];
            $data['attribute'][$j] = $array['attribute_'. $i];
            $data['input_type'][$j] = ($array['input_type_'. $i] == 1) ? 1: 2;
            $data['text_value'][$j] =($array['input_type_'. $i] == 1)? $array['text_value_'. $i]: '';
            $data['bool_value'][$j] = ($array['input_type_'. $i] == 2) ? $array['bool_value_'.$i]: null;
            $data['units'][$j] = $array['units_'. $i];
            $data['attribute_id'][$j] = isset($array['attribute_id_'. $i]) ? $array['attribute_id_'. $i]: null;
            $j++;
        }

        return $data;
    }

    private function setAttributeIds(Request $request)
    {
        // dd($request);
        $array = $request->toArray();
        $data = [];
        $j = 0;

        for($i = 0 ; $i < 10; $i++) {
           
            $data[$j] = isset($array['attribute_id_'. $i]) ? $array['attribute_id_'. $i]: null;
          
            $j++;
        }

        return $data;
    }
   
}
