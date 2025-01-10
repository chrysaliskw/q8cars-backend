<?php

namespace App\Services\Api\User;

use App\Models\OfferRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmitReviewService
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
        $review = new Review();
        $review->car_id =  $this->request->car_id;
        $review->car_version_id =  $this->request->car_version_id;
        $review->user_id = Auth::id();
        $review->short_comment =  $this->request->short_comment;
        $review->detailed_comment =  $this->request->detailed_comment;
        $review->rating =  $this->request->rating;
        $review->status = Review::STATUS_SUBMITTED;
        $review->saveOrFail();
        return true;
    }
}
