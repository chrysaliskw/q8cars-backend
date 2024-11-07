<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelatedNewsController extends ApiBaseController
{
    public function __invoke()
    {
        $news = News::where('page', News::RELATED_NEWS_PAGE)->active()->limit(3)->get();
        $data = NewsResource::collection($news);
        return $this->success(['data' => $data], 'Related News', Response::HTTP_OK);
    }
}
