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

        $color = json_decode($carVersion->colours, true);
        $colorArray = array_combine(range(1, count($color)), array_values($color));
        $colors = [];
        foreach($colorArray as $c) {
            $colors[] = config('params.colors')[$c];
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

        return view('admin.car.car-version.edit', compact('car','carVersion', 'carVarient', 
            'currentFuel', 'currentTransmission', 'currentBodyType', 'currentBrand', 'currentCar',
            'currentColors', 'selectedColorsCount', 'ccount', 'carVarientName',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarVersionRequest $request, CarVersion $carVersion)
    {
        // dd($request);
        $car = $carVersion->car;
        if($carVersion->is_car_spec == CarVersion::CAR_SPECIFICATION) {
            $carVersion = null;
        }
        try 
        {
            $service = new CarService($request->validated(), $car, $carVersion);
            $carVersion = $service->saveToCarVersion(); 
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
}
