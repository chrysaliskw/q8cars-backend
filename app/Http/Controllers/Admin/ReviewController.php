<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Exception;
use App\DataGrids\Admin\ReviewDataGrid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new ReviewDataGrid(request()->query());

        return view('admin.reviews.index', compact('grid'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        $viewData = [
            'id' => $review->id,
            'User Mobile' =>  $review->user ? "<a href='" . route('admin.user.show', $review->user->id) . "'>{$review->user->phone_code} {$review->user->mobile}</a>": 'NA',
            'User Name' =>  $review->user ? "<a href='" . route('admin.user.show', $review->user->id) . "'>{$review->user->name} </a>":'NA',
            'Car Model' => $review->car->model_name,
            'Car Brand' => "<a href='" . route('admin.brand.show', $review->car->brand->id) . "'>{$review->car->brand->name}</a>" ,
            'Title' => $review->short_comment,
            'Description' => $review->detailed_comment,
            'Rating' => $review->rating,
            'Status' => config('params.review.status')[$review->status],
            'Created At' =>  dateTimeFormat($review->created_at),
            'Updated At' => dateTimeFormat($review->updated_at),

        ];
        return view('admin.reviews.show', compact('viewData','review'));
    }

    public function update(Request $request)
    {
        $review = Review::find($request->id);
        $oldStatus = $review->status;
       DB::beginTransaction();
       try {
        $review->status = $request->status;
        $review->save();
        if($review->status == Review::STATUS_VERIFIED && $oldStatus !=  Review::STATUS_VERIFIED)
        {
            $review->car->avg_rating = (($review->car->avg_rating* $review->car->total_reviews_count) + ($review->rating)) /($review->car->total_reviews_count +1);
            $review->car->total_reviews_count = $review->car->total_reviews_count+1;
            $review->car->save();
        }
        DB::commit();
       } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Status Updation Failed.']);

       }
       
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}