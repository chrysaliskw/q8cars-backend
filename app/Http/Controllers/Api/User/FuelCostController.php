<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\User\Car\FuelCostService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FuelCostController extends ApiBaseController
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'car_version_id' => 'required|exists:car_versions,id',
            'kms_per_day' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $kmsPerDay = $request->input('kms_per_day');
            $carVersionId = $request->input('car_version_id');

            $service = new FuelCostService($request);
            $fuelCostPerMonth = $service->calculateFuelCostPerMonth($kmsPerDay, $carVersionId);

            return $this->success(['data' => $fuelCostPerMonth], 'Fuel Cost per month calculated successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
