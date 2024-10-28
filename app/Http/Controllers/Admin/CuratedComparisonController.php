<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\DataGrids\Admin\CuratedComparisonGrid;
use App\Http\Requests\Admin\CuratedCompareRequest;
use App\Http\Requests\AdminCuratedCompareRequest;
use App\Models\CuratedComparison;
use App\Services\Admin\CuratedComparisonService;
use Exception;

class CuratedComparisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new CuratedComparisonGrid(request()->query());
        return view('admin.curated-comparison.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.curated-comparison.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CuratedCompareRequest $request)
    {
        if (
            $request->car_1_id == $request->car_2_id ||
            $request->car_1_id == $request->car_3_id ||
            $request->car_2_id == $request->car_3_id
        ) {

            return redirect()->route('admin.curated-comparison.create')->with('error', 'Each Compare Model must be different!')->withInput()->withErrors([
                'car_1_id' => 'Each Compare Model must be different!',
                'car_2_id' => 'Each Compare Model must be different!',
                'car_3_id' => 'Each Compare Model must be different!'
            ]);
        }   
    
        try {
     
            $service = new CuratedComparisonService();
            $curatedComparison = $service->create($request);
            // dd($curatedComparison);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error') )->withInput();
        }
        return redirect()->route('admin.curated-comparison.index', $curatedComparison)->with('success', 'Curated Comparison Created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CuratedComparison $curatedComparison)
    {

        $viewData = [
            'Brand 1' => empty($curatedComparison->brand_id_1) ? 'NIL' : $curatedComparison->brand1->name,
            'Brand 2' => empty($curatedComparison->brand_id_2) ? 'NIL' : $curatedComparison->brand2->name,
            'Brand 3' => empty($curatedComparison->brand_id_3) ? 'NIL' : $curatedComparison->brand3->name,
            'Car 1' => empty($curatedComparison->car_id_1) ? 'NIL' : $curatedComparison->car1->model_name,
            'Car 2' => empty($curatedComparison->car_id_2) ? 'NIL' : $curatedComparison->car2->model_name,
            'Car 3' => empty($curatedComparison->car_id_3) ? 'NIL' : $curatedComparison->car3->model_name,
            'Content' => empty($curatedComparison->content) ? 'NIL' : $curatedComparison->content,
            'Source' => empty($curatedComparison->source) ? 'NIL' : $curatedComparison->source,
            'title' => empty($curatedComparison->title) ? 'NIL' : $curatedComparison->title,
            'image_1' => empty($curatedComparison->image_1) ? 'NIL' : $curatedComparison->image_1,
            'image_2' => empty($curatedComparison->image_2) ? 'NIL' : $curatedComparison->image_2,
            'image_3' => empty($curatedComparison->image_3) ? 'NIL' : $curatedComparison->image_3,
            'status' => empty($curatedComparison->status) ? 'NIL' : ($curatedComparison->status == 1 ? 'Active' : 'Inactive')
        ];
        

        return view('admin.curated-comparison.show', compact('curatedComparison', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $curatedComparison = CuratedComparison::findOrFail($id);
        if (!$curatedComparison) {
            return redirect()->route('admin.curated-comparison.index')->with('error', 'Curated Comparison not found');
        }
        $currentCarModel1 = $curatedComparison->car1 ? json_encode([
            'id' => $curatedComparison->car_id_1,
            'text' => $curatedComparison->car1->model_name
        ]) : null;

        $currentCarModel2 = $curatedComparison->car2 ? json_encode([
            'id' => $curatedComparison->car_id_2,
            'text' => $curatedComparison->car2->model_name
        ]) : null;


        $currentCarModel3 = $curatedComparison->car3 ? json_encode([
            'id' => $curatedComparison->car_id_3,
            'text' => $curatedComparison->car3->model_name
        ]) : null;

        $currentbrand1 = $curatedComparison->brand1 ? json_encode([
            'id' => $curatedComparison->brand_id_1,
            'text' => $curatedComparison->brand1->name
        ]) : null;

        $currentbrand2 = $curatedComparison->brand2 ? json_encode([
            'id' => $curatedComparison->brand_id_2,
            'text' => $curatedComparison->brand2->name
        ]) : null;
        $currentbrand3 = $curatedComparison->brand3 ? json_encode([
            'id' => $curatedComparison->brand_id_3,
            'text' => $curatedComparison->brand3->name
        ]) : null;


        return view('admin.curated-comparison.edit', compact('curatedComparison', 'currentCarModel1', 'currentCarModel2', 'currentCarModel3', 'currentbrand1', 'currentbrand2', 'currentbrand3'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CuratedCompareRequest $request, CuratedComparison $curatedComparison)
    {
        try {
            $service = new CuratedComparisonService();
            $service->update($request, $curatedComparison);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'));
        }
        return redirect()->route('admin.curated-comparison.index')->with('success', 'Curated Comparison Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CuratedComparison $curatedComparison)
    {
        try {
            $curatedComparison->delete();
        } catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.curated-comparison.index')->with('success', 'Curated Comparison deleted successfully!');
    }
    
}
