<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\UserNotificationMapping;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
      
        $readStatus = UserNotificationMapping::where('user_id',Auth::id())->where('notification_id',$this->id)->read_status;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => file_asset('files-notifications', $this->image),
            'logo' => file_asset('files-notifications', $this->logo),
            'business_name' => $this->business_name,
            'status' => $this->status,
            'read_status' => $readStatus,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
