<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CarResource;
use App\Services\Api\User\Car\SearchService;
use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CarVersionResource;

class CarSearchController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $result = null;
        $result = (new SearchService($request))->handle();

        return CarResource::collection($result)
            ->additional([
                'message' => 'Cars search listing',
                'status' => Response::HTTP_OK
            ]);
    }

    public function getCarVersion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $car = Car::find($request->car_id);

        $carVersions = $car->carVersions;
        return CarVersionResource::collection($carVersions)
        ->additional([
            'message' => 'Car Versions listing',
            'status' => Response::HTTP_OK
        ]);
    }
}