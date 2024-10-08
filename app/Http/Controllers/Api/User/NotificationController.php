<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NotificationResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;

class NotificationController extends ApiBaseController
{

    /**
     * Display a listing of the resource.
     */
    public function __invoke()
    {
        $userId = Auth::id();
        $currentDate = now();

        $notifications = Notification::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', true)->where('start_date', '>=', $currentDate)->where('end_date', '>=', $currentDate)->latest()->paginate(20);

        $data = NotificationResource::collection($notifications);

        return $this->success(['data' => $data], 'Notification Listing', Response::HTTP_OK);

    }

}
