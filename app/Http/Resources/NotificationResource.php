<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $readStatus = $this->users->isNotEmpty() ? $this->users->first()->pivot->read_status : 0;

        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => file_asset('files-notif', $this->image),
            'logo' => file_asset('files-notif', $this->logo),
            'business_name' => $this->business_name,
            'status' => $this->status,
            'read_status' => $readStatus,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
