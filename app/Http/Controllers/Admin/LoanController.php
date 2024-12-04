<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\Bank\LoanService;
use App\Services\Common\EmiCalculatorService;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banks = Bank::all();
        return view('admin.loan-info.show', compact('banks'));
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
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'bank_id' => 'required|exists:banks,id',
            'base_gross_income' => 'required|numeric|min:0|max:99999999',
            'loanTenureYears' => 'required|integer|min:1|max:7',
            'base_interest_rate' => 'required|numeric|min:7|max:100',
            'base_other_emi' => 'nullable|numeric|min:0|max:99999999',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $banks = Bank::all();

        $bankId = $request->bank_id;
        $bank = Bank::find($bankId);

        $service = new LoanService($request, $bankId);

        $emiService = $service->calculateEmiSchedule();

        $loanEligibility = $service->calculateLoanEligibility();

        // dd($loanEligibility);


        if (isset($emiService['error'])) {
            return redirect()->back()->withErrors(['message' => $emiService['error']])->withInput();
        }

        if (isset($loanEligibility['error'])) {
            return redirect()->back()->withErrors(['message' => $loanEligibility['error']])->withInput();
        }

        return view('admin.loan-info.show', compact('emiService', 'banks', 'loanEligibility', 'input'));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
