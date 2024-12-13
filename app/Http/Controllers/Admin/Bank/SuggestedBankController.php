<?php

namespace App\Http\Controllers\Admin\Bank;

use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BankSuggestionRequest;
use App\DataGrids\Admin\SuggestedBanksDataGridNew;

class SuggestedBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new SuggestedBanksDataGridNew(request()->query());
        return view('admin.banks.suggested-banks.index', compact('grid'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BankSuggestionRequest $suggested_bank)
    {
        $viewData = [
            'Requested User' => empty($suggested_bank->user) ? 'NIL' : $suggested_bank->user->name,
            'Full Name' => empty($suggested_bank->first_name) && empty($suggested_bank->last_name) ? 'NIL' : trim($suggested_bank->first_name . ' ' . $suggested_bank->last_name),
            'Civil ID' => empty($suggested_bank->civil_id) ? 'NIL' : $suggested_bank->civil_id,
            'Email' => empty($suggested_bank->email) ? 'NIL' : $suggested_bank->email,
            'Bank Name' => empty($suggested_bank->bank_name) ? 'NIL' : $suggested_bank->bank_name,
            'Status' => $suggested_bank->status == BankSuggestionRequest::STATUS_SUBMITTED ? 'Submitted' :
                       ($suggested_bank->status == BankSuggestionRequest::STATUS_ACCEPTED ? 'Accepted' :
                       ($suggested_bank->status == BankSuggestionRequest::STATUS_REJECTED ? 'Rejected' : 'unknown')),
            'Created At' => dateTimeFormat($suggested_bank->created_at),
            'Updated At' => dateTimeFormat($suggested_bank->updated_at),
        ];

        return view('admin.banks.suggested-banks.show', compact('suggested_bank', 'viewData'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, BankSuggestionRequest $suggested_bank)
    // {
        //     $suggested_bank = BankSuggestionRequest::find($request->id);
        //     $suggested_bank->status = $request->status;
        //     $suggested_bank->save();
        //     return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        // }

        public function update(Request $request, BankSuggestionRequest $suggested_bank)
        {
            // $suggested_bank = BankSuggestionRequest::find($request->id);

            // $settings = SmtpSetting::checkSmtpConfig();
            // $details = [];
            // if ($settings) {
            //     if ($request->status == BankSuggestionRequest::STATUS_ACCEPTED) {
            //         $details = [
            //             'title' => 'Bank Suggestion Request',
            //             'status' => $request->status,
            //             'page'  => 'emails.admin.bank.bank_accepted',
            //             'cc' => [],
            //         ];
            //     } elseif($request->status == BankSuggestionRequest::STATUS_REJECTED) {
            //         $details = [
            //             'title' => 'Bank Suggestion Request',
            //             'status' => $request->status,
            //             'page'  => 'emails.admin.bank.bank_rejected',
            //             'cc' => [],
            //         ];
            //     }
            // }

            // try {
            //     $suggested_bank->status = $request->status;
            //     $suggested_bank->save();
            //     dispatch(new SendAdminMailJob($details, $suggested_bank->email));
            //     return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
            // } catch (\Exception $e) {
            //     Log::info($e->getMessage());
            //     return back()->with('failed', 'Failed! there is some issue with email provider');
            // }

            $suggested_bank = BankSuggestionRequest::find($request->id);

            // $settings = SmtpSetting::checkSmtpConfig();
            // $details = [];

            // if ($settings) {

                Log::info('Status in request: ' . $request->status);
                if ($request->status == BankSuggestionRequest::STATUS_ACCEPTED) {
                    $page = 'emails.admin.bank.bank_accepted';
                } elseif ($request->status == BankSuggestionRequest::STATUS_REJECTED) {
                    $page = 'emails.admin.bank.bank_rejected';
                } else {
                    $page = null;
                }

                $details = [
                    'title' => 'Bank Suggestion Request',
                    'page'  => $page,
                    'suggested_bank' => $suggested_bank,
                ];
            // }

            try {
                $suggested_bank->status = $request->status;
                $suggested_bank->save();

                if($page){
                    dispatch(new SendAdminMailJob($details, $suggested_bank->email));
                    return response()->json(['success' => true, 'message' => 'Bank suggestion request status updated and email sent.']);
                }
                return response()->json(['success' => true, 'message' => 'Bank suggestion request status updated.']);

            } catch (\Exception $e) {
                Log::info($e->getMessage());
                return back()->with('failed', 'Failed! There is some issue with email provider.');
            }
        }
    }
