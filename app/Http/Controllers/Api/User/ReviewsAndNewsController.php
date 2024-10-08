<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Car;
use App\Http\Resources\ReviewResource;
use App\Models\News;
use App\Models\RecentSearch;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
   
class ReviewsAndNewsController extends ApiBaseController
{
    
    public function __invoke(Request $request)
    { 

      $data['reviews'] = $this->getReviews($request);
      $data['news'] = $this->getNews($request);
      return $this->success(['data' => $data], 'Recviews And News', Response::HTTP_OK);

    }

    private function getReviews(Request $request)
    {
        $result = Review::where('status', Review::STATUS_VERIFIED)
            ->orderBy('sort_order', 'asc')
            ->when($request->search, function($query, $value) {
                $query->where('detailed_comment', 'like', '%' . $value . '%')
                ->orWhere('short_comment', 'like', '%' . $value . '%');
            })            
            ->limit(20)
            ->get();

        return  ReviewResource::collection($result);

    }
    private function getNews(Request $request)
    {
        $result = News::active()->published()
            ->when($request->search, function($query, $value) {
                $query->where('content', 'like', '%' . $value . '%')
                ->orWhere('title', 'like', '%' . $value . '%');
            })
            ->limit(4)->orderBy('posted_time', 'asc')
            ->limit(20)
            ->get();
        return  NewsResource::collection($result);
    }

    public function getSuggestions()
    {
        $data['recent-searches'] = RecentSearch::where('user_id',Auth::id())->orderBy('id','Desc')->limit(2)->pluck('key_word');
        $data['trending-searches'] = Car::active()->orderBy('search_view_count','Desc')->limit(5)->pluck('model_name');
        return $this->success(['data' => $data], 'Recent And Trending Seraches', Response::HTTP_OK);
    }
}