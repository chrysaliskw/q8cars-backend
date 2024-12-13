<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\SubmitReviewService;

class SubmitReviewController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'car_version_id' => 'required|integer',
            'short_comment' => ['required', 'string'],
            'detailed_comment' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $service = new SubmitReviewService($request);
            $res = $service->handle();
            return $this->success(['data' => []], 'Review Submitted Successfully', Response::HTTP_OK);
        } catch (Exception $e) {
            logger($e);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
