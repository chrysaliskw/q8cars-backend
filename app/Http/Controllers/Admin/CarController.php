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
use App\Models\CarComparisonList;
use App\Services\Admin\CarService;
use Illuminate\Support\Facades\DB;
use App\DataGrids\Admin\CarDataGrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarRequest;
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
        try 
        {
            $service = new CarService($request->validated());
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
        $carVersions = CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)->get();

        return view('admin.car.show', compact('car','carVersions','fuelTypes', 'transmissionTypes', 'carVarient', 'colors', 'professions'));
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
        for ($i = $car->carImages->count() + 1; $i <= Car::MAX_NUM_IMAGES; $i++) {
            $carImages->push(new CarImage());
        }

        $carVideos = collect(CarImage::where('car_id', $car->id)->video()->get());
        for ($i = $car->carVideos->count() + 1; $i <= 3; $i++) {
            $carVideos->push(new CarImage());
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
            
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, Car $car)
    {
       
        try 
        {
            $service = new CarService($request->validated(), $car);
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

            $car->carImages->delete();
            $car->news->delete();
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
}
