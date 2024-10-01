<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\OfferResource;
use App\Models\Car;
use App\Models\Offer;
use App\Http\Resources\OfferDetailsResource;

class OfferController extends ApiBaseController
{
    
    public function index(Request $request)
    {
        $data['popular_offers']= $this->getPopularOffers($request);
        $data['popular_brands'] = $this->getPopularBrandOffers($request);
        $data['recent_offers'] = $this->getRecentOffers($request);
        $data['suggested_offers'] = $this->getSuggestedOffers($request);
        return $this->success(['data' => $data], 'Offer listing!', Response::HTTP_OK);
    }

    public function show($id,Request $request)
    {
        $offer = Offer::find($id); 
        if($request->car_version_id)
        {
           
            $offer = Offer::where('car_version_id',$request->car_version_id)
                ->where('car_id',$offer->car_id)
                ->active()
                ->where('start_date', '<=', today()) 
                ->where('end_date', '>=',today())
                ->orderBy('view_count','Desc')
                ->first();
        }
        if($offer){
            $data = OfferDetailsResource::make($offer);
        }else{
            $data =[];
        }
        return $this->success(['data' =>  $data], 'Offer Details!', Response::HTTP_OK);
    }

    private function getPopularOffers(Request $request)
    {
            $offers = Offer::active()
                ->where('start_date', '<=', today()) 
                ->where('end_date', '>=',today())
                ->when($request->brand_id, function ($query) use ($request) {
                    $query->where('brand_id', $request->brand_id); 
                })
                ->when($request->car_id, function ($query) use ($request) {
                    $query->where('car_id', $request->car_id); 
                })
                ->orderBy('view_count','Desc')
                ->paginate(10);
        return OfferResource::collection($offers);
    }

    private function getPopularBrandOffers(Request $request)
    {
        $popularCars = Car::when($request->brand_id, function ($query) use ($request) {
                        $query->where('brand_id', $request->brand_id); 
                    })
                    ->when($request->car_id, function ($query) use ($request) {
                        $query->where('id', $request->car_id); 
                    })->orderBy('view_count','Desc')->pluck('id');

        $offers = Offer::active()
            ->whereIn('car_id',$popularCars)
            ->where('start_date', '<=', today()) 
            ->where('end_date', '>=',today())
            ->orderBy('view_count','Desc')
            ->paginate(10);
        return OfferResource::collection($offers);

    }

    private function getRecentOffers(Request $request)
    {
        $offers = Offer::active()
            ->with('car')
            ->where('start_date', '<=', today()) 
            ->where('end_date', '>=',today())
            ->when($request->brand_id, function ($query) use ($request) {
                $query->where('brand_id', $request->brand_id); 
            })
            ->when($request->car_id, function ($query) use ($request) {
                $query->where('car_id', $request->car_id); 
            })
            ->orderBy('id','Desc')
            ->paginate(10);
        return OfferResource::collection($offers); 
    }

    private function getSuggestedOffers(Request $request)
    {
        $offers = Offer::active()
            ->with('car')
            ->where('start_date', '<=', today()) 
            ->where('end_date', '>=',today())
            ->when($request->car_id, function ($query) use ($request) {
                $query->where('car_id', $request->car_id); 
            })
            ->when($request->brand_id, function ($query) use ($request) {
                $query->where('brand_id', $request->brand_id); 
            })
            ->where('show_in_suggestions',Offer::SHOW_IN_SUGGESTIONS)
            ->orderBy('id','Desc')
            ->paginate(10);
        return OfferResource::collection($offers); 
    }
}