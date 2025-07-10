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
use Illuminate\Log\Logger;
use App\Models\CarFavourite;
use App\Models\OfferRequest;
use App\Models\View360Image;
use Illuminate\Http\Request;
use App\Imports\CarBulkImport;
use Illuminate\Validation\Rule;
use App\Models\BrandColorMapping;
use App\Models\CarComparisonList;
use App\Services\Admin\CarService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\DataGrids\Admin\CarDataGrid;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\CarRequest;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use App\Models\CarAdditonalSpecifications;
use App\DataGrids\Admin\CarVersionDataGrid;
use App\Exceptions\FuelTtypeAndTransmissionException;
use Illuminate\Http\Exceptions\PostTooLargeException;

class CarController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if($request->hasFile('file')){

            try {
                $file = $request->file('file');
                $filename = 'car_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('car_uploads', $filename);
                $fullPath = storage_path("app/{$path}");

                $spreadsheet = IOFactory::load($fullPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $etaMinutes = ceil(($highestRow * 0.2) / 60);

                // $import = new CarBulkImport();
                $import = new CarBulkImport(Auth::id());
                Bus::chain([
                    fn () => Excel::queueImport($import, $fullPath),
                    fn () => Storage::delete($path),
                ])->dispatch();

                return back()->with('success', "File is being processed in the background. Estimated time: ~{$etaMinutes} minutes.");
            } catch (\Exception $e) {
                return back()->with('error', 'Error during import: ' . $e->getMessage());
            }
        } else {
            Log::warning('No file uploaded.');
            return back()->with('error', 'No file uploaded.');
        }
    }

    public function clearImportStatus(Request $request)
    {
        Cache::forget('car_import_result_' . Auth::id());
        return response()->json(['status' => 'ok']);
    }

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
        // dd($request->all());
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '60M');
        $rows = $request->row_count;

        $rules = [];
        for ($i = 0; $i < $rows; $i++) {
            $rules["attribute_id_$i"] = 'nullable|integer';
            $rules["attribute_$i"] = 'required|string|max:255';
            $rules["input_type_$i"] = ['required', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])];
            $rules["section_$i"] = 'required|integer';
            $rules["text_value_$i"] = 'nullable|string';
            $rules["bool_value_$i"] = 'nullable|integer';
            $rules["units_$i"] = 'nullable|string';
            $rules["key_feature_$i"] = 'nullable|integer';
            $rules["key_spec_$i"] = 'nullable|integer';
            $rules["icon_$i"] = 'required_with:key_feature_' . $i . ',key_spec_' . $i . '|mimes:jpg,png,jpeg|max:2048';

            $inputType = $request->input("input_type_$i");
            if ($inputType == CarAdditonalSpecifications::TYPE_TEXT) {
                $rules["text_value_$i"] = 'required|string';
                $rules["bool_value_$i"] = 'nullable|integer';
            } elseif ($inputType == CarAdditonalSpecifications::TYPE_BOOLEAN) {
                $rules["bool_value_$i"] = 'required|integer';
                $rules["text_value_$i"] = 'nullable|string';
            } else {
                $rules["text_value_$i"] = 'nullable|string';
                $rules["bool_value_$i"] = 'nullable|integer';
            }
        }

        // Apply the validator with dynamic rules
        $validator = Validator::make($request->all(), $rules, [], [
            'attribute_*' => 'Specification Title',
            'input_type_*' => 'input type',
            'section_*' => 'Category',
            'text_value_*' => 'Value(Text)',
            'bool_value_*' => 'Value(Boolean)',
            'units_*' => 'units',
            'key_feature*' => 'key feature',
            'key_spec*' => 'key spec',
            'icon.*' => 'icon',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
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

        $attributeData = $this->setAttributes($validator->validated(), $rows);

        $data = array_merge($request->validated(), $attributeData);

        // Initialize dynamic attribute IDs
        for ($i = 0; $i < $rows; $i++) {
            $data['attribute_id'][$i] = null;
        }
        $colorsAvailable = BrandColorMapping::where('brand_id', $request->brand_id)->pluck('id')->toArray();
        $rules = [];
        foreach ($colorsAvailable as $id) {
            $rules["colors_image_{$id}"] = 'mimes:jpg,png,jpeg|max:2048';
        }
        $validatedData = $request->validate($rules);
        $data = array_merge($data, $validatedData);
        $data['row_count'] = $rows;
        // dd($data);
        try {
            $service = new CarService($data);
            $car = $service->handle();
        } catch (PostTooLargeException $ex) {
            logger($ex);
            return back()->with('error', 'File size should be within 2MB')->withInput();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with(
                'error',
                __('app.error') . $ex
            )->withInput();
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
        $fuelTypes = [];
        foreach ($newArray as $fuelType) {
            $fuelTypes[] = config('params.car.fuel_type')[$fuelType];
        }

        $travel_type = json_decode($car->travel_type, true);
        $travel_types = [];
        if ($travel_type) {
            $travel_typeArray = array_combine(range(1, count($travel_type)), array_values($travel_type));
            foreach ($travel_typeArray as $c) {
                $travel_types[] = config('params.car.travel_type')[$c];
            }
        }

        $transmission = json_decode($car->transmission_type, true);
        $newAtransmissionarray = array_combine(range(1, count($transmission)), array_values($transmission));
        $transmissionTypes = [];
        foreach ($newAtransmissionarray as $transmissionType) {
            $transmissionTypes[] = config('params.car.transmission_type')[$transmissionType];
        }
        $colors = [];



        $color = !empty($car->colours)?json_decode($car->colours, true):[];
        if(!empty($color)){

        $colorArray = array_combine(range(1, count($color)), array_values($color));

        // foreach ($colorArray as $c) {

        //     $colors[] = BrandColorMapping::find($c)->name;
        // }
        foreach ($colorArray as $c) {
            $brandColor = BrandColorMapping::find($c); // Find the BrandColorMapping record
            if ($brandColor) {
                $colors[] = $brandColor->name; // Add the name if the record exists
            }
        }
          }
        $profession = json_decode($car->professions, true);
        $professions = [];
        if ($profession) {
            $professionArray = array_combine(range(1, count($profession)), array_values($profession));
            foreach ($professionArray as $c) {
                $professions[] = config('params.professions')[$c];
            }
        }
        $colorsAvailable = BrandColorMapping::where('brand_id', $car->brand_id)->pluck('name', 'id')->toArray();
        $carVarient = $car->carSpec;
        $carVersion = $carVarient;
        $carVersions = CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)->get();

        return view('admin.car.show', compact('car', 'carVersions', 'fuelTypes', 'transmissionTypes', 'carVarient', 'colors', 'professions', 'carVersion', 'colorsAvailable', 'travel_types'));
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
        // for ($i = $carImages->count() + 1; $i <= Car::MAX_NUM_IMAGES; $i++) {
        //     $carImages->push(new CarImage());
        // }
        $carVideos = collect(CarImage::where('car_id', $car->id)->video()->get());
        // for ($i = $car->carVideos->count() + 1; $i <= 3; $i++) {
        //     $carVideos->push(new CarImage());
        // }

        $additionals = collect(CarAdditonalSpecifications::where('car_id', $car->id)->where('car_version_id', $carVarient->id)->get());
        for ($i = $carVarient->carAdditionalSpecifications->count() + 1; $i <= 10; $i++) {
            $additionals->push(new CarAdditonalSpecifications());
        }
        // dd($additionals[0]);
        $currentProfessions = [];
        $selectedProfessionCount = 0;
        if ($car->professions) {
            foreach (json_decode($car->professions) as $p) {
                array_push($currentProfessions, $p);
                $selectedProfessionCount++;
            }
        }
        $pcount = count(config('params.professions'));

        $currentFuels = [];
        $selectedFuelCount = 0;
        foreach (json_decode($car->fuel_types) as $f) {
            array_push($currentFuels, $f);
            $selectedFuelCount++;
        }
        $fcount = count(config('params.car.fuel_type'));

        // $currentTravel = [];
        // $selectedTravelCount = 0;
        // foreach(json_decode($car->travel_type) as $tr) {
        //    array_push($currentTravel, $tr);
        //    $selectedTravelCount++;
        // }
        // $trcount = count(config('params.car.travel_type'));

        $currentTravel = [];
        $selectedTravelCount = 0;
        $travelTypes = json_decode($car->travel_type); // Decode the travel_type JSON
        if (is_array($travelTypes)) { // Check if it's a valid array
            foreach ($travelTypes as $tr) {
                array_push($currentTravel, $tr);
                $selectedTravelCount++;
            }
        }
        $trcount = count(config('params.car.travel_type')); // Count the travel types from config


        $currentTransmissions = [];
        $selectedTransmissionCount = 0;
        foreach (json_decode($car->transmission_type) as $t) {
            array_push($currentTransmissions, $t);
            $selectedTransmissionCount++;
        }
        $tcount = count(config('params.car.transmission_type'));

        $currentColors = [];
        $selectedColorsCount = 0;
        $colorsAvailable = BrandColorMapping::where('brand_id', $car->brand_id)->pluck('name', 'id')->toArray();
        foreach (json_decode($car->colours) as $c) {
            array_push($currentColors, $c);
            $selectedColorsCount++;
        }
        $ccount = count($colorsAvailable);

        return view('admin.car.edit', compact(
            'car',
            'carVarient',
            'currentBrand',
            'currentBodyType',
            'carImages',
            'carVideos',
            'pcount',
            'selectedProfessionCount',
            'currentProfessions',
            'selectedFuelCount',
            'currentFuels',
            'fcount',
            'tcount',
            'selectedTransmissionCount',
            'currentTransmissions',
            'ccount',
            'selectedColorsCount',
            'currentColors',
            'additionals',
            'colorsAvailable',
            'currentTravel',
            'selectedTravelCount',
            'trcount'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, Car $car)
    {

        $rows = $request->row_count;

        $rules = [];
        for ($i = 0; $i < $rows; $i++) {
            $rules["attribute_id_$i"] = 'nullable|integer';
            $rules["attribute_$i"] = 'required|string|max:255';
            $rules["input_type_$i"] = ['required', Rule::in([CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])];
            $rules["section_$i"] = 'required|integer';
            $rules["text_value_$i"] = 'nullable|string';
            $rules["bool_value_$i"] = 'nullable|integer';
            $rules["units_$i"] = 'nullable|string';
            $rules["key_feature_$i"] = 'nullable|integer';
            $rules["key_spec_$i"] = 'nullable|integer';
            $rules["icon_$i"] = 'nullable|mimes:jpg,png,jpeg|max:2048';

            $inputType = $request->input("input_type_$i");
            if ($inputType == CarAdditonalSpecifications::TYPE_TEXT) {
                $rules["text_value_$i"] = 'required|string';
                $rules["bool_value_$i"] = 'nullable|integer';
            } elseif ($inputType == CarAdditonalSpecifications::TYPE_BOOLEAN) {
                $rules["bool_value_$i"] = 'required|integer';
                $rules["text_value_$i"] = 'nullable|string';
            } else {
                $rules["text_value_$i"] = 'nullable|string';
                $rules["bool_value_$i"] = 'nullable|integer';
            }
        }

        // Apply the validator with dynamic rules
        $validator = Validator::make($request->all(), $rules, [], [
            'attribute_*' => 'Specification Title',
            'input_type_*' => 'input type',
            'section_*' => 'Category',
            'text_value_*' => 'Value(Text)',
            'bool_value_*' => 'Value(Boolean)',
            'units_*' => 'units',
            'key_feature*' => 'key feature',
            'key_spec*' => 'key spec',
            'icon.*' => 'icon',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
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

        $attributeData = $this->setAttributes($validator->validated(), $rows);

        $data = array_merge($request->validated(), $attributeData);
        $colorsAvailable = BrandColorMapping::where('brand_id', $car->brand_id)->pluck('id')->toArray();
        $rules = [];
        foreach ($colorsAvailable as $id) {
            $rules["colors_image_{$id}"] = 'mimes:jpg,png,jpeg';
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }
        $validatedData = $validator->validated();
        $data = array_merge($data, $validatedData);
        $data['update'] = 1;
        $carVarient = $car->carSpec;
        $data['row_count'] = $rows;
        //    dd($data);
        try {
            $service = new CarService($data, $car, $carVarient);
            $car = $service->handle();
        }   catch (PostTooLargeException $ex) {
            logger($ex);
            return back()->with('error', 'File size should be within 2MB')->withInput();
        }
        catch (FuelTtypeAndTransmissionException $e) {
            logger($e);
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        } catch (Exception $ex) {
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
        if (CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)->count()) {
            return back()->with('error', 'Cannot delete car, versions exists')->withInput();
        }

        DB::beginTransaction();
        try {

            $car->carImages()->delete();
            $car->news()->delete();
            $car->carVersions()->delete();
            Faq::where('car_id', $car->id)->delete();
            CarFavourite::where('car_id', $car->id)->delete();
            Review::where('car_id', $car->id)->delete();
            TestDrive::where('car_id', $car->id)->delete();
            CarView::where('car_id', $car->id)->delete();
            CarAdditonalSpecifications::where('car_id', $car->id)->delete();
            CarComparisonList::where('car_1_id', $car->id)->orWhere('car_2_id', $car->id)->delete();
            OfferRequest::where('car_id', $car->id)->delete();
            View360Image::where('car_id', $car->id)->delete();
            $car->delete();

            DB::commit();
        } catch (Exception $ex) {
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

        $query = Car::where('model_name', 'like', "%$term%")->active()->launched();
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }
        $cars = $query->select(['id', 'model_name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cars;
        $response['pagination'] = ['more' => !empty($cars) ?? false];
        return $response;
    }

    private function categoryAttributeHasError($key, $errors)
    {
        if (empty($errors)) {
            return false;
        }

        $result = array_filter($errors, function ($a) use ($key) {
            return preg_match("!^{$key}.!", $a);
        }, ARRAY_FILTER_USE_KEY);
        if (empty($result)) {
            return false;
        }

        return array_values($result)[0][0];
    }

    public function setAttributes($array, $rows)
    {
        //    dd($array);
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

        for ($i = 0; $i < $rows; $i++) {
            // dd($array['key_feature_'.$i]);
            $data['section'][$j] = $array['section_' . $i] ?? '';
            $data['attribute'][$j] = $array['attribute_' . $i];
            $data['input_type'][$j] = isset($array['input_type_' . $i]) && ($array['input_type_' . $i] == 1) ? 1 : 2;
            $data['text_value'][$j] = isset($array['input_type_' . $i]) && ($array['input_type_' . $i] == 1) ? $array['text_value_' . $i] : '';
            $data['bool_value'][$j] =  isset($array['input_type_' . $i]) && ($array['input_type_' . $i] == 2) ? $array['bool_value_' . $i] : null;
            $data['units'][$j] = $array['units_' . $i] ?? '';
            $data['attribute_id'][$j] = isset($array['attribute_id_' . $i]) ? $array['attribute_id_' . $i] : null;
            $data['key_feature'][$j] = isset($array['key_feature_' . $i]) ? $array['key_feature_' . $i] : 0;
            $data['key_spec'][$j] = isset($array['key_spec_' . $i]) ? $array['key_spec_' . $i] : 0;
            $data['icon'][$j] = isset($array['icon_' . $i]) ? $array['icon_' . $i] : null;
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

        for ($i = 0; $i < 10; $i++) {

            $data[$j] = isset($array['attribute_id_' . $i]) ? $array['attribute_id_' . $i] : null;

            $j++;
        }

        return $data;
    }

    public function add360ViewImages(Request $request)
    {
        $car = Car::find($request->id);
        $images = View360Image::where('car_id', $car->id)->where('type',View360Image::TYPE_IMAGE)->active()->get();
        $url = View360Image::where('car_id', $car->id)->where('type',View360Image::TYPE_URL)->active()->first();

        return view('admin.car.add-360-view', compact('car', 'images','url'));
    }

    public function store360ViewImages(Request $request)
    {
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '60M');
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:20480', // 20MB max
        ]);

        if ($request->hasFile('picture')) {
            $image = new View360Image();
            $path = $request->picture->store(View360Image::FILE_DIR);
            $image->image = basename($path);
            $image->car_id = $request->id;
            $image->type = View360Image::TYPE_IMAGE;
            $image->save();
            // Return the image URL
            return response()->json([
                'success' => true,
                'image_url' => file_asset('files-360_view', $image->image), // Use 'storage' to generate a public URL
            ]);
        }
        return response()->json(['success' => false, 'message' => 'No file uploaded.'], 400);
    }

    public function add360Url(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'id' => 'required',
        ]);

        $image = View360Image::where('car_id',$request->id)->first()?? new View360Image();
        $image->image = $request->url;
        $image->type = View360Image::TYPE_URL;
        $image->car_id = $request->id;
        $image->save();
        return response()->json([
                'success' => true,
                'id' => $image->id,
                'image_url' => $image->image, // Use 'storage' to generate a public URL
            ]);
    }
    public function delete360ViewImage(Request $request)
    {
        $image = View360Image::find($request->image_id);
        $image->delete();
        return response()->json([
            'success' => true,

        ]);
    }
    public function update360ViewImage(Request $request)
    {
        $request->validate([
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:20480', // 20MB max
        ]);

        if ($request->hasFile('picture')) {
            $image = View360Image::find($request->image_id);
            $path = $request->picture->store(View360Image::FILE_DIR);
            $image->image = basename($path);
            $image->save();

            return response()->json([
                'success' => true,
                'image_url' => file_asset('files-360_view', $image->image),
            ]);
        }
        return response()->json(['success' => false, 'message' => 'No file uploaded.'], 400);
    }

    public function deleteCarVideo(Request $request)
    {
        DB::beginTransaction();
        try {
            $video = CarImage::find($request->id);
            $car = Car::find($video->car_id);
            $video->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.car.show', compact('car'))->with('success', 'Car video deleted successfully!');
    }
}
