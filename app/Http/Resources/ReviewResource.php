<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
        'id' => $this->id,
        'car_id' => $this->car_id,
        'car_version_id' => $this->car_version_id,
        'user_id' => $this->user_id,
        'user_name' => $this->user->name ?? '',
        'user_profile_picture' => $this->user->picture ? file_asset('files-user', $this->user->picture) : null, 
        'short_comment' => $this->short_comment,
        'detailed_comment' => $this->detailed_comment,
        'rating' => $this->rating,
        'status' => $this->status,
        'status_text' => config('params.review.status')[$this->status],
        'created_at' => get_time_ago($this->created_at),
    ];
  }
}
