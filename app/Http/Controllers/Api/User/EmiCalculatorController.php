<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Car;
use App\Models\EmiInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\Common\EmiCalculatorService;
use App\Http\Controllers\Api\ApiBaseController;

class EmiCalculatorController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'principal' => ['nullable', 'numeric'],
            'annualInterestRate' => ['nullable', 'numeric'],
            'loanTenureYears' => ['nullable', 'integer'],
            'car_id' => ['required'],
            'car_version_id' => ['nullable']
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $car = Car::find($request->car_id);
        $on_road_price = $car->on_road_price;

        // if ($request->principal > $on_road_price) {
        //     return $this->error(
        //         "The principal amount cannot be greater than the on-road price of the car. On-road price: KWD $on_road_price", Response::HTTP_UNPROCESSABLE_ENTITY);
        // }

        if(!isset($request->principal) || !isset($request->annualInterestRate) || !isset($request->loanTenureYears)) {
            $request->merge($this->getCarBaseEmiCalcualtions($request->car_id, $request->car_version_id));
        }

        $service = new EmiCalculatorService($request);
        $result = $service->handle();

        return $this->success(['data' => $result], 'EMI Calculator!', Response::HTTP_OK);
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
