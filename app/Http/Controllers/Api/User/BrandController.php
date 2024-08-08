<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
   
class BrandController extends ApiBaseController
{
    
public function __invoke(Request $request)
    {
        $brands = Brand::active()
            ->select(['id', 'name', 'icon', 'is_top_brand'])
            ->when($request->has('search') && !empty($request->input('search')), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            })
            ->paginate(50);
        return $this->success([
            'data' => $brands,
            'common_data' => ['base_img_url' => file_asset('files-brand')]
        ], 'Brand listing', Response::HTTP_OK);
    }
}