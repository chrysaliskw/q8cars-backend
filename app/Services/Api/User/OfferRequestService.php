<?php

namespace App\Services\Api\User;

use App\Models\OfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferRequestService
{

     /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

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
        $offerRequest = new OfferRequest();
        $offerRequest->car_id =  $this->request->car_id;
        $offerRequest->car_version_id =  $this->request->car_version_id;
        $offerRequest->user_id = Auth::id();
        $offerRequest->offer_id =  $this->request->offer_id;
        $offerRequest->type =  $this->request->type;
        $offerRequest->full_name =  $this->request->full_name;
        $offerRequest->phone_code = $this->request->phone_code;
        $offerRequest->mobile =  $this->request->mobile;
        $offerRequest->email = $this->request->email;
        $offerRequest->saveOrFail();  
        return true;
    }

}
