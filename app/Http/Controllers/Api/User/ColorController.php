<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;

class ColorController extends ApiBaseController
{
    public function getAllColors()
    {
        $colors = DB::table('brand_color_mappings')
            ->select('code', 'name')
            ->distinct('code')
            ->get();

        return $this->success(['data' => $colors], 'Colors listing', Response::HTTP_OK);
    }
}
