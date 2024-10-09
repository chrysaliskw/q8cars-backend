<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Models\FavouriteComparison;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\FavouriteComparisonResource;

class FavouriteComparisonController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $userId = Auth::id();

        $comparisons = FavouriteComparison::where('user_id', $userId)->with(['car1', 'car2', 'car3', 'car4'])->get();

        $data = FavouriteComparisonResource::collection($comparisons);

        return $this->success(['data' => $data], 'Favourite Comparison Listing', Response::HTTP_OK);
    }

}
