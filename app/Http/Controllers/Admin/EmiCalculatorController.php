<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmiInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\Common\EmiCalculatorService;

class EmiCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->merge(['car_id' => 1, 'car_version_id' => 1]);
        // $request->merge($this->getCarBaseEmiCalcualtions(1, 1));
        // $service = new EmiCalculatorService($request);
        // $result = $service->handle();

        return view('admin.emi-info.show');
    }

public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand_id' => 'required',
            'car_id' => 'required',
            'car_version_id' => 'nullable',
            'principal' => 'required|numeric',
            'loanTenureYears' => 'required|integer|min:1|max:7',
            'annualInterestRate' => 'required|numeric',
        ]);

        $car = Car::find($request->car_id);
        $on_road_price = $car->on_road_price;

        if ($validatedData['principal'] > $on_road_price) {
            return redirect()->back()
                ->withErrors(['principal' => 'The principal amount cannot be greater than the on road price of the car. On road price : KWD '.$on_road_price])
                ->withInput();
        }

        $service = new EmiCalculatorService($request);
        $result = $service->handle();

        if (isset($result['error'])) {
            return redirect()->back()
                ->withErrors(['message' => $result['error']])
                ->withInput();
        }

        // Redirect to show page with query parameters
        return redirect()->route('admin.emi-info.index', [
            'brand_id' => $request->brand_id,
            'brand_id_text' => $request->brand_id_text,
            'car_id' => $request->car_id,
            'car_id_text' => $request->car_id_text,
            'car_version_id' => $request->car_version_id,
            'car_version_id_text' => $request->car_version_id_text,
            'principal' => $request->principal,
            'loanTenureYears' => $request->loanTenureYears,
            'annualInterestRate' => $request->annualInterestRate,
        ])->with(['result' => $result]);
    }
    private function hasRequiredParameters($input)
    {
        $required = [
            'brand_id',
            'car_id',
            'principal',
            'loanTenureYears',
            'annualInterestRate'
        ];
        
        foreach ($required as $param) {
            if (!isset($input[$param]) || empty($input[$param])) {
                return false;
            }
        }
        
        return true;
    }
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $input = $request->all();

    //     $validatedData = $request->validate([
    //         'brand_id' => 'required',
    //         'car_id' => 'required',
    //         'car_version_id' => 'nullable',
    //         'principal' => 'required|numeric',
    //         'loanTenureYears' => 'required|integer|min:1|max:7',
    //         'annualInterestRate' => 'required|numeric',
    //     ]);

    //     $car = Car::find($request->car_id);
    //     $on_road_price = $car->on_road_price;

    //     if ($validatedData['principal'] > $on_road_price) {
    //         return redirect()->back()->withErrors(['principal' => 'The principal amount cannot be greater than the on road price of the car. On road price : KWD '.$on_road_price])->withInput();
    //     }

    //     $service = new EmiCalculatorService($request);
    //     $result = $service->handle();

    //     if (isset($result['error'])) {
    //         return redirect()->back()->withErrors(['message' => $result['error']])->withInput();
    //     }
    //     session()->put('form_data', $request->all());

    //     return view('admin.emi-info.show', compact('result', 'input'))->with('form_data', session('form_data'));
    // }
    

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
    // public function getCarBaseEmiCalcualtions($carId)
    // {
    //     $emiInfo = EmiInfo::where('car_id', $carId)->first();
    //     // if($carVersionId) {
    //     //     $emiInfo = EmiInfo::where('car_version_id', $carVersionId)->first();
    //     // }

    //     if(!$emiInfo) {
    //         return [];
    //     }

    //     $result['principal'] = $emiInfo->principal_loan_amount;
    //     // $result['loan_amount'] = $emiInfo->loan_amount;
    //     $result['loanTenureYears'] = $emiInfo->loan_tenure_year;
    //     $result['annualInterestRate'] = $emiInfo->interest_rate;

    //     return $result;
    // }
}
