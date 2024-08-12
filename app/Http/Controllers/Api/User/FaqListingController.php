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
        FaqListResource::collection($faqs);
        return $this->success(['data' => $faqs], 'Faq List!', Response::HTTP_OK);
    }
}