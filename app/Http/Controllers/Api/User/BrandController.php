<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\BrandResource;
   
class BrandController extends ApiBaseController
{
    
public function __invoke(Request $request)
    {
        $brands = Brand::active()
            ->when($request->has('search') && !empty($request->input('search')), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->paginate(50);
         BrandResource::collection($brands);
        return $this->success(['data' => $brands], 'Brand listing!', Response::HTTP_OK);
    }
}