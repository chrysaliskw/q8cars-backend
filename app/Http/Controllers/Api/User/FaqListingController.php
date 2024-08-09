<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\FaqListResource;
   
class FaqListingController extends ApiBaseController
{
    
public function __invoke(Request $request)
    {
        $faqs = Faq::active()->paginate(20);
        $res = FaqListResource::collection($faqs);
     
        return $this->success(['data' => $res], 'Faq List!', Response::HTTP_OK);
    }
}