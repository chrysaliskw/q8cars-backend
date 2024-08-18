<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
        'brand_id' => $this->brand_id,
        'brand_name' => $this->brand->name,
        'name' => $this->model_name,
        'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
        'on_road_price' => 'KWD ' . $this->on_road_price,
        'finance_available' => 'KWD '. $this->finance_available,
        'rating' => $this->avg_rating,
        'total_reviews_count' => $this->total_reviews_count,
        'image' => file_asset('files-car', $this->image),
        'is_favourite' => $this->is_favourite,
    ];
  }
}
