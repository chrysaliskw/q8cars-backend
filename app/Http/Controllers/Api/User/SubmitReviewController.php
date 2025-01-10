<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\Car;
use App\Models\User;
use App\Models\Review;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\SubmitReviewService;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\CarVersion;

class SubmitReviewController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'car_version_id' => 'required|integer',
            'short_comment' => ['nullable', 'string'],
            'detailed_comment' => ['nullable', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $service = new SubmitReviewService($request);
            $service->handle();

            // $settings = SmtpSetting::checkSmtpConfig();
            // if ($settings) {
            $car = Car::find($request->car_id);
            $carVersion = CarVersion::find($request->car_version_id);
            $user = Auth::user();
            $review = Review::where('user_id', Auth::id())
                ->where('status', Review::STATUS_SUBMITTED)
                ->latest()
                ->first();

            if ($review && isset($user->email)) {
                $details = [
                    'title' => 'New Car Review Submitted',
                    'page' => 'emails.admin.review.review_submitted',
                    'car_model' => $car ? $car->model_name : 'Unknown Car',
                    'user_name' => $user ? $user->name : 'Unknown',
                    'car_version' => $carVersion ? $carVersion->varient_name : 'Unknown Car Version',
                ];
                dispatch(new SendAdminMailJob($details, Auth::user()->email));
            }

            // }

            return $this->success(['data' => []], 'Review Submitted Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            logger($e);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
