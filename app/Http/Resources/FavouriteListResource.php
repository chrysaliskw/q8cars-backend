<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteListResource extends JsonResource
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
        'id' => $this->car_id,
        'slug' => $this->car->slug,
        'favourite_id' => $this->id,
        'model_name' => $this->car->model_name,
        'brand_id' => $this->car->brand_id,
        'brand_name' => $this->car->brand->name,
        'ex_showroom_price' => $this->car->ex_showroom_price,
        'on_road_price' => $this->car->on_road_price,
        'review_count' => $this->car->total_reviews_count,
        'avg_rating' => $this->car->avg_rating,
        'image' => $this->car->image ? file_asset('files-car', $this->car->image) : null, 
        'isFavourite' => true,
    
    ];
  }
}
