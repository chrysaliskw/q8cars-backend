<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\OfferResource;
use App\Models\Car;
use App\Models\Offer;

class OfferController extends ApiBaseController
{
    
public function __invoke(Request $request)
    {
        $offers = Offer::active()
            ->where('start_date', '<=', today()) 
            ->where('end_date', '>=',today())
            ->when($request->car_id, function ($query) use ($request) {
                $query->where('car_id', $request->car_id); 
            })
            ->paginate(10);
        OfferResource::collection($offers);
        return $this->success(['data' => $offers], 'Offer listing!', Response::HTTP_OK);
    }
}