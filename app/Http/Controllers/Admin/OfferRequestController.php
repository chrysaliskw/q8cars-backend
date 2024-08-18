<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Exception;
use App\DataGrids\Admin\OfferRequestDataGrid;
use App\Models\OfferRequest;
use Illuminate\Http\Request;

class OfferRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new OfferRequestDataGrid(request()->query());

        return view('admin.offer-requests.index', compact('grid'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OfferRequest $offerRequest)
    {
        $viewData = [
           
            'User Mobile' =>  $offerRequest->user ? "<a href='" . route('admin.user.show', $offerRequest->user->id) . "'>{$offerRequest->user->phone_code} {$offerRequest->user->mobile}</a>": 'NA',
            'Car Model' => $offerRequest->car->model_name,
            'Requested Name' => $offerRequest->full_name ,
            'Requeted Mobile' => $offerRequest->mobile,
            'Requeted Email' => $offerRequest->email,
            'Offer' => $offerRequest->type == OfferRequest::TYPE_OFFER ? $offerRequest->offer_id : '',
            // 'status_key' => $testDrive->status,
            'Type' => config('params.offer_request.type')[$offerRequest->type],
            'Created At' =>  dateTimeFormat($offerRequest->created_at),
            'Updated At' => dateTimeFormat($offerRequest->updated_at),

        ];
        return view('admin.offer-requests.show', compact('viewData'));
    }

}