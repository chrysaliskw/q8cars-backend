<?php

namespace App\Http\Controllers\Admin\Bank;

use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use App\Mail\Admin\LoanRequestMail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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
            'Requested Email' => empty($loan_request->email) ? 'NIL' : $loan_request->email,
            'Requested Mobile' => empty($loan_request->contact_number) ? 'NIL' : $loan_request->contact_number,
            'Bank Name' => empty($loan_request->bank) ? 'NIL' : $loan_request->bank->bank_name,
            'Status' => $loan_request->status == BankSuggestionRequest::STATUS_SUBMITTED ? 'Submitted' : ($loan_request->status == BankSuggestionRequest::STATUS_ACCEPTED ? 'Accepted' : ($loan_request->status == BankSuggestionRequest::STATUS_REJECTED ? 'Rejected' : 'unknown')),
            'Created At' => dateTimeFormat($loan_request->created_at),
            'Updated At' => dateTimeFormat($loan_request->updated_at),
        ];

        return view('admin.banks.loan-requests.show', compact('loan_request', 'viewData'));
    }

    public function update(Request $request, BankSuggestionRequest $loan_request)
    {
        $loan_request = BankSuggestionRequest::find($request->id);

        // $settings = SmtpSetting::checkSmtpConfig();
        // $details = [];

        // if ($settings) {

            Log::info('Status in request: ' . $request->status);
            if ($request->status == BankSuggestionRequest::STATUS_ACCEPTED) {
                $page = 'emails.admin.loan.loan_accepted';
            } elseif ($request->status == BankSuggestionRequest::STATUS_REJECTED) {
                $page = 'emails.admin.loan.loan_rejected';
            } else {
                $page = null;
            }

            $details = [
                'title' => 'Loan Request',
                'page'  => $page,
                'loan_request' => $loan_request,
            ];
        // }

        try {
            $loan_request->status = $request->status;
            $loan_request->save();

            if($page){
                dispatch(new SendAdminMailJob($details, $loan_request->email));
                return response()->json(['success' => true, 'message' => 'Loan request status updated and email sent.']);
            }
            return response()->json(['success' => true, 'message' => 'Loan request status updated.']);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return back()->with('failed', 'Failed! There is some issue with email provider.');
        }
    }


}
