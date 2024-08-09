<?php

namespace App\Services\Api\User;

use App\Models\CarFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateFavouriteService
{

     /**
     * @var \Illuminate\Http\Request
     */
    protected $request;
    protected $favourite;

   /**
     * Creates a new instance
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function handle()
    {    
        $favourite = CarFavourite::where('user_id',Auth::id())->where('car_id', $this->request->car_id)->first();
        if($favourite)
        {
            $isFavourite = false;
            $favourite->delete();
            $msg = 'Removed from favourites!';
        }else{
            $favourite = new CarFavourite();
            $favourite->car_id = $this->request->car_id;
            $favourite->user_id = Auth::id();
            $favourite->saveOrFail();
            $isFavourite = true;
            $msg = 'Added to favourites!';
        }
        return $res=[
            'isFavourite' => $isFavourite,
            'msg' => $msg
        ];
    }

}
