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
        $user = Auth::user();
        $currentDate = now()->toDateString();

        $notifications = Notification::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->active()->where('start_date', '<=', $currentDate)->where('end_date', '>=', $currentDate)->latest()->paginate(20);

        // $data = NotificationResource::collection($notifications)->additional(['is_mute' =>$user->is_mute]);

        // return $this->success(['data' => $data], 'Notification Listing', Response::HTTP_OK);

        return NotificationResource::collection($notifications)->additional(['is_mute' => (bool) $user->is_mute]);

    }

    public function toggleMute()
    {
        $user = Auth::user();

        if ($user) {
            $user->is_mute = !$user->is_mute;
            $user->save();

            return $this->success(['data' => ['is_mute' => $user->is_mute]], 'Mute status updated successfully.', Response::HTTP_OK);
        }

        return $this->error('User not found.', Response::HTTP_NOT_FOUND);
    }

}
