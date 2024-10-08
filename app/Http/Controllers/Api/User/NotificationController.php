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

        $notifications = Notification::where('user_id', $userId)->where('status', true)->where('end_date', '>=', $currentDate)->latest()->paginate(20);

        $data['notifications'] = NotificationResource::collection($notifications);

        return $this->success(['data' => $data], 'Notification Listing', Response::HTTP_OK);

    }

}
