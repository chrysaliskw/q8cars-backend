<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\OfferService;
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
        // dd($request->all());
        try {
            $service = new OfferService();
            $offer = $service->create($request);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.offers.index', $offer)->with('success', 'Offer created successfully!');
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
            'Title' =>  $offer->title,
            'Key Feature 1' => empty($offer->key_feature_1) ? 'NIL' : $offer->html_key_feature_1,
            'Key Icon 1' => $offer->key_icon_1,
            'Key Feature 2' => empty($offer->key_feature_2) ? 'NIL' : $offer->html_key_feature_2,
            'Key Icon 2' =>  $offer->key_icon_2,
            'Description' =>  $offer->html_description,
            'Start date' => empty($offer->start_date) ? 'NIL' : dateTimeFormat($offer->start_date),
            'End date' => empty($offer->end_date) ? 'NIL' : dateTimeFormat($offer->end_date),
            'View Count' => $offer->view_count,
            'Show in suggestions' => config('params.offers.show_in_suggestions')[$offer->show_in_suggestions],
            'Status' => config('params.offers.status')[$offer->status],
            'Created At' => dateTimeFormat($offer->created_at),
            'Updated At' => dateTimeFormat($offer->updated_at),
        ];
        // dd($viewData);
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
        try {
            $service = new OfferService();
            $service->update($request, $offer);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully!');
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
