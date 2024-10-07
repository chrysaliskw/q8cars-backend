<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmiInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Common\EmiCalculatorService;

class EmiCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge(['car_id' => 1, 'car_version_id' => 1]);
        $request->merge($this->getCarBaseEmiCalcualtions(1, 1));
        $service = new EmiCalculatorService($request);
        $result = $service->handle();

        return view('admin.emi-info.show', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.emi-info.show', compact('result'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EmiInfo $emiInfo)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmiInfo $emiInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmiInfo $emiInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmiInfo $emiInfo)
    {
        //
    }

     /**
     * @return array
     */
    public function getCarBaseEmiCalcualtions($carId, $carVersionId)
    {
        $emiInfo = EmiInfo::where('car_id', $carId)->first();
        if($carVersionId) {
            $emiInfo = EmiInfo::where('car_version_id', $carVersionId)->first();
        }

        if(!$emiInfo) {
            return [];
        }

        $result['principal'] = $emiInfo->principal_loan_amount;
        // $result['loan_amount'] = $emiInfo->loan_amount;
        $result['loanTenureYears'] = $emiInfo->loan_tenure_year;
        $result['annualInterestRate'] = $emiInfo->interest_rate;

        return $result;
    }
}
