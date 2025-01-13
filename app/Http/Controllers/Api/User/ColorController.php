<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Models\BrandColorMapping;

class ColorController extends ApiBaseController
{
    public function getAllColors()
    {
        $colors = BrandColorMapping::active()
            ->select('code', 'name')
            ->groupBy('code','name')
            ->get();

        return $this->success(['data' => $colors], 'Colors listing', Response::HTTP_OK);
    }
}
