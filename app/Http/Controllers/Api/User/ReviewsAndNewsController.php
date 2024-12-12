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
use Illuminate\Support\Facades\Validator;

class ReviewsAndNewsController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        if ($request->id) {
            $data['news'] = $this->getNews($request);
        } else {
            $data['reviews'] = $this->getReviews($request);
            $data['news'] = $this->getNews($request);
        }
      return $this->success(['data' => $data], 'Reviews And News', Response::HTTP_OK);

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
        if ($request->id) {
            $news = News::active()->published()
                ->where('id', $request->id)
                ->first();

            return $news ? new NewsResource($news) : [];
        }
        $result = News::active()->published()
            ->when($request->search, function($query, $value) {
                $query->where('content', 'like', '%' . $value . '%')
                ->orWhere('title', 'like', '%' . $value . '%');
            })
            ->orderByDesc('posted_time')
            ->limit(20)
            ->get();
        return  NewsResource::collection($result);
    }

    public function getSuggestions()
    {
        $data['recent-searches'] = RecentSearch::where('user_id',Auth::id())->orderBy('id','Desc')->limit(2)->pluck('key_word');
        $data['trending-searches'] = Car::active()->launched()->orderBy('search_view_count','Desc')->limit(5)->pluck('model_name');
        return $this->success(['data' => $data], 'Recent And Trending Searches', Response::HTTP_OK);
    }

    public function addSuggestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $search = new RecentSearch();
        $search->user_id = Auth::id();
        $search->key_word = $request->search;
        $search->save();
        return $this->success(['data' => []], 'Added to Recent Searches', Response::HTTP_OK);
    }
}
