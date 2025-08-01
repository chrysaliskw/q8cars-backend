<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BrandResource;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class BrandController extends ApiBaseController
{

public function __invoke(Request $request)
    {

        $brands = Brand::active()
            ->when($request->has('search') && !empty($request->input('search')), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->when($request->is_just_launched, function($query, $value) {
                $query->whereHas('cars', function($query) {
                    $query->where('cars.is_just_launched', Car::JUST_LAUNCHED);
                });
            })
            ->when($request->is_recently_purchased, function($query, $value) {
                $query->where('brands.is_recently_purchased', Brand::RECENT_PURCHASED);
            })
            ->when($request->is_top_brand, function($query, $value) {
                $query->where('brands.is_top_brand', Brand::TOP_BRAND);
            })->get();


            // ->paginate(50);
        BrandResource::collection($brands);
        return $this->success(['data' => $brands], 'Brand listing!', Response::HTTP_OK);
    }

    public function popularBrands()
    {
        $popularBrands = Brand::withCount(['cars as total_views' => function($query) {
            $query->select(DB::raw('SUM(view_count)'));
        }])
        ->orderBy('total_views', 'desc') // Order brands by total views in descending order
        ->paginate(50); // Paginate results
        return $this->success(['data' => BrandResource::collection($popularBrands)], 'Popular Brand listing!', Response::HTTP_OK);
    }

}
