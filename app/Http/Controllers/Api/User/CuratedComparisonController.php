<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\CuratedComparisonResource;
use App\Models\CuratedComparison;

class CuratedComparisonController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        // Get all curated comparisons that are active and have a published date less than or equal to today
        // Order them by id in descending order (newest first)
        $query = CuratedComparison::active()
            ->orderBy('id', 'Desc')
            ->where('published_date', '<=', date('Y-m-d'));
        $comarisons = $query->limit(3)->get();

        if ($request->is_paginate == 1) {
            $comarisons = $query->paginate(10);
        }
        return $this->success([
            'data' =>  CuratedComparisonResource::collection($comarisons)
        ], 'Curated Comparisons listing', Response::HTTP_OK);
    }
}
