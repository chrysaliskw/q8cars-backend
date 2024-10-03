<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataGrids\Admin\OfferDataGrid;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\OfferRequest;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new OfferDataGrid(request()->query());

        return view('admin.offers.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.offers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
            $data['key_icon_1'] = $request->file('key_icon_1')->store('offers/icons', 'public');
        }

        if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
            $data['key_icon_2'] = $request->file('key_icon_2')->store('offers/icons', 'public');
        }

        try{
            Offer::create($data);
        }

        catch(Exception $ex){
        logger($ex);
        return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        $viewData = [
            'Brand' => empty($offer->brand) ? 'NIL' : $offer->brand->name,
            'Car' => empty($offer->car) ? 'NIL' : $offer->car->model_name,
            'Car Version' => empty($offer->carVersion) ? 'NIL' : $offer->carVersion->varient_name,
            'Title' => empty($offer->title) ? 'NIL' : $offer->title,
            'Image' => empty($offer->image) ? 'NIL' : '<img src="' . asset('storage/' . $offer->image) . '" alt="Offer Image" style="max-width: 200px;"/>',
            'Key Feature 1' => empty($offer->key_feature_1) ? 'NIL' : $offer->key_feature_1,
            'Key Icon 1' => empty($offer->key_icon_1) ? 'NIL' : '<img src="' . asset('storage/' . $offer->key_icon_1) . '" alt="Offer Image" style="max-width: 200px;"/>',
            'Key Feature 2' => empty($offer->key_feature_2) ? 'NIL' : $offer->key_feature_2,
            'Key Icon 2' => empty($offer->key_icon_2) ? 'NIL' :'<img src="' . asset('storage/' . $offer->key_icon_2) . '" alt="Offer Image" style="max-width: 200px;"/>',
            'Description' => empty($offer->description) ? 'NIL' : $offer->description,
            'HTML Description' => empty($offer->html_description) ? 'NIL' : $offer->html_description,
            'Start date' => empty($offer->start_date) ? 'NIL' : $offer->start_date,
            'End date' => empty($offer->end_date) ? 'NIL' : $offer->end_date,
            'Status' => config('params.offers.status')[$offer->status],
            'View Count' => $offer->view_count,
            'Show in suggestions' => config('params.offers.show_in_suggestions')[$offer->show_in_suggestions],
        ];

        return view('admin.offers.show', compact('offer', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        $currentBrand = null;
        $currentBrand = json_encode([
            'id' => $offer->brand_id,
            'text' => $offer->brand->name
        ]);
        $currentCar = null;
        $currentCar = json_encode([
            'id' => $offer->car_id,
            'text' => $offer->car->model_name
        ]);

        return view('admin.offers.edit', compact('offer', 'currentBrand', 'currentCar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($offer->image) {
                Storage::disk('public')->delete($offer->image);
            }
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        if ($request->hasFile('key_icon_1') && $request->file('key_icon_1')->isValid()) {
            if ($offer->key_icon_1) {
                Storage::disk('public')->delete($offer->key_icon_1);
            }
            $data['key_icon_1'] = $request->file('key_icon_1')->store('offers/icons', 'public');
        }

        if ($request->hasFile('key_icon_2') && $request->file('key_icon_2')->isValid()) {
            if ($offer->key_icon_2) {
                Storage::disk('public')->delete($offer->key_icon_2);
            }
            $data['key_icon_2'] = $request->file('key_icon_2')->store('offers/icons', 'public');
        }

        try{
            $offer->update($data);
        }

        catch(Exception $ex){
        logger($ex);
        return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        try {
            $offer->delete();
        } catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully!');
    }
}
