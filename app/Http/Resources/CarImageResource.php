<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class CarImageResource extends JsonResource
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
        'color' => $this->color,
        'color_name' => $this->color ? config('params.colors')[$this->color] : null,
        'type' => $this->type,
        'section' => $this->section,
        'section_name' => $this->section ? config('params.car.image-section')[$this->section] : null,
        'file_name' => file_asset('files-car', $this->file_name),
        'thumbnail' => $this->thumbnail ? file_asset('files-car', $this->thumbnail) : null,
        'video_title' => $this->video_title,
        'video_description' => $this->video_description,
        'video_view_count' => $this->video_view_count,
        'video_posted_date' => $this->video_posted_date ? date('M d, Y', strtotime($this->video_posted_date)) : null,
        'video_posted_media' => $this->video_posted_media,
    ];
  }
}
