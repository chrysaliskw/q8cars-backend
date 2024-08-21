<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use App\Services\Admin\CarService;
use App\DataGrids\Admin\CarDataGrid;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarRequest;

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
            $service = new CarService($request->validate());
            $car = $service->handle();
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
        $fuelTypes = " ";
        foreach($newArray as $fuelType) {
            $fuelTypes = config('params.car.fuel_type')[$fuelType] . " " .$fuelTypes;
        }

        $transmission = json_decode($car->transmission_type, true);
        $newAtransmissionarray = array_combine(range(1, count($transmission)), array_values($transmission));
        $transmissionTypes = " ";
        foreach($newAtransmissionarray as $transmissionType) {
            $transmissionTypes = config('params.car.transmission_type')[$transmissionType] . " " .$transmissionTypes;
        }

        return view('admin.car.show', compact('car', 'fuelTypes', 'transmissionTypes'));
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
        return view('admin.car.edit', compact('car', 'currentBrand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        try 
        {
            if($request->hasfile('image')){
                $request->image->store(Car::FILE_DIR);
                $car->image = $request->image->hashName();  
               
           }
           $car->brand_id = $request->brand_id;  
           $car->save();
             
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
        //
    }
}
