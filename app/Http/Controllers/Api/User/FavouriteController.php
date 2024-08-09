<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\CarFavourite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FavouriteListResource;
use App\Services\Api\User\UpdateFavouriteService;

class FavouriteController extends ApiBaseController
{
    /**
     * Displays the authenticated users profile info.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favourites = CarFavourite::where('user_id',Auth::id())->get();
        $res = FavouriteListResource::collection($favourites);
        return $this->success(['data' => $res], 'Favourite List!', Response::HTTP_OK);
    }

    /**
     * Updates user profile info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try
        {
            $service = new UpdateFavouriteService($request);
            $res = $service->handle();   
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        return $this->success(['data' => ['isFavourite' => $res['isFavourite']]], $res['msg'], Response::HTTP_OK);
    }
}
