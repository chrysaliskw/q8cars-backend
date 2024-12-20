<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Offer;

use App\Models\TestDrive;
use App\Models\OfferRequest;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\DataGrids\Admin\OfferRequestDataGrid;

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

            'User Mobile' =>  $offerRequest->user ? "<a href='" . route('admin.user.show', $offerRequest->user->id) . "'>{$offerRequest->user->phone_code} {$offerRequest->user->mobile}</a>" : 'NA',
            'Car Model' => $offerRequest->car ? $offerRequest->car->model_name : 'NA',
            'Requested Name' => $offerRequest->full_name ?? 'NA',
            'Requested Mobile' => $offerRequest->mobile ?? 'NA',
            'Requested Email' => $offerRequest->email ?? 'NA',
            // 'Offer' => $offerRequest->type == OfferRequest::TYPE_OFFER ? $offerRequest->offer_id : '',
            // 'Status' =>config('params.offer_request.status')[$offerRequest->status],
            // 'Type' => config('params.offer_request.type')[$offerRequest->type],
            // 'Created At' =>  dateTimeFormat($offerRequest->created_at),
            // 'Updated At' => dateTimeFormat($offerRequest->updated_at),
        ];

        if ($offerRequest->type == OfferRequest::TYPE_OFFER && $offerRequest->offer_id) {
            $viewData['Offer'] = Offer::where('id', $offerRequest->offer_id)->value('title');
        }
        $viewData['Status'] = config('params.offer_request.status')[$offerRequest->status] ?? 'NA';
        $viewData['Type'] = config('params.offer_request.type')[$offerRequest->type] ?? 'NA';
        $viewData['Created At'] = dateTimeFormat($offerRequest->created_at) ?? 'NA';
        $viewData['Updated At'] = dateTimeFormat($offerRequest->updated_at) ?? 'NA';

        return view('admin.offer-requests.show', compact('viewData', 'offerRequest'));
    }
    public function update(Request $request)
    {
        $offerRequest = OfferRequest::find($request->id);

        // $settings = SmtpSetting::checkSmtpConfig();
        // $details = [];

        // if ($settings) {

        Log::info('Status in request: ' . $request->status);
        if ($request->status == OfferRequest::STATUS_COMPLETED) {
            $page = 'emails.admin.offer_request.offer_request_completed';
        } elseif ($request->status == OfferRequest::STATUS_CANCELLED) {
            $page = 'emails.admin.offer_request.offer_request_cancelled';
        } else {
            $page = null;
        }

        $details = [
            'title' => 'Offer Request',
            'page'  => $page,
            'offerRequest' => $offerRequest,

        ];
        // }

        try {
            $offerRequest->status = $request->status;
            $offerRequest->save();

            if ($page) {
                dispatch(new SendAdminMailJob($details, $offerRequest->email));
                return response()->json(['success' => true, 'message' => 'Offer request status updated and email sent.']);
            }
            return response()->json(['success' => true, 'message' => 'Offer request status updated.']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return back()->with('failed', 'Failed! There is some issue with email provider.');
        }
        // $offerRequest = OfferRequest::find($request->id);
        // $offerRequest->status = $request->status;
        // $offerRequest->save();
        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
