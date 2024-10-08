<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = Notification::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->latest()->paginate(20);

        return response()->json($notifications, 200);
    }


}
