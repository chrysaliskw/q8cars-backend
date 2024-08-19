<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use Illuminate\Http\Request;
use App\DataGrids\Admin\CarDataGrid;
use App\Http\Controllers\Controller;

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
    public function show(Car $car)
    {
        return view('admin.car.show', compact('car'));
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
                $car->brand_id = $request->brand_id;  
                $car->save();
           }
             
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
