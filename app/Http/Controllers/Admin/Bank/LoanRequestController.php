<?php

namespace App\Http\Controllers\Admin\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BankSuggestionRequest;
use App\DataGrids\Admin\LoanRequestDataGrid;

class LoanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new LoanRequestDataGrid(request()->query());
        return view('admin.banks.loan-requests.index', compact('grid'));
    }


    /**
     * Display the specified resource.
     */
    public function show(BankSuggestionRequest $loan_request)
    {
        $viewData = [
            'Requested User' => empty($loan_request->user) ? 'NIL' : $loan_request->user->name,
            'Requested Name' => empty($loan_request->first_name) && empty($loan_request->last_name) ? 'NIL' : trim($loan_request->first_name . ' ' . $loan_request->last_name),
            'Email' => empty($loan_request->email) ? 'NIL' : $loan_request->email,
            'Contact Number' => empty($loan_request->contact_number) ? 'NIL' : $loan_request->contact_number,
            'Bank Name' => empty($loan_request->bank) ? 'NIL' : $loan_request->bank->bank_name,
            'Status' => $loan_request->status == BankSuggestionRequest::STATUS_SUBMITTED ? 'Submitted' :
                       ($loan_request->status == BankSuggestionRequest::STATUS_ACCEPTED ? 'Accepted' :
                       ($loan_request->status == BankSuggestionRequest::STATUS_REJECTED ? 'Rejected' : 'unknown')),
            'Created At' => dateTimeFormat($loan_request->created_at),
            'Updated At' => dateTimeFormat($loan_request->updated_at),
        ];

        return view('admin.banks.loan-requests.show', compact('loan_request', 'viewData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankSuggestionRequest $loan_request)
    {
        $loan_request = BankSuggestionRequest::find($request->id);
        $loan_request->status = $request->status;
        $loan_request->save();
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
