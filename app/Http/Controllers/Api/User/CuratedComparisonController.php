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
       $query = CuratedComparison::active()->orderBy('id','Desc');
       $comarisons = $query->limit(3)->get();

       if($request->is_paginate == 1)
       {
        $comarisons = $query->paginate(10);
      
       }
        return $this->success([
           'data' =>  CuratedComparisonResource::collection($comarisons)
        ], 'Curated Comparisons listing', Response::HTTP_OK);
    }
}