<?php

namespace App\Http\Controllers\Admin\Bank;

use App\DataGrids\Admin\SuggestedBanksDataGridNew;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankSuggestionRequest;
use Illuminate\Http\Request;


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
            'Status' => $suggested_bank->status == BankSuggestionRequest::STATUS_SUBMITTED ? 'Submitted' : ($suggested_bank->status == BankSuggestionRequest::STATUS_ACCEPTED ? 'Accepted' : ($suggested_bank->status == BankSuggestionRequest::STATUS_REJECTED ? 'Rejected' : 'unknown')),
            'Created At' => dateTimeFormat($suggested_bank->created_at),
            'Updated At' => dateTimeFormat($suggested_bank->updated_at),
        ];

        return view('admin.banks.suggested-banks.show', compact('suggested_bank', 'viewData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankSuggestionRequest $suggested_bank)
    {
        $suggested_bank = BankSuggestionRequest::find($request->id);
        $suggested_bank->status = $request->status;
        $suggested_bank->save();
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function select(Request $request)
    {
        $page = $request->query('page');
        $term = $request->query('search');
        $countryId = $request->query('country_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = Bank::where('bank_name', 'like', "%$term%")->active();
        $banks = $query->select(['id', 'bank_name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $banks;
        $response['pagination'] = ['more' => !empty($banks) ?? false];
        return $response;
    }
}
