<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class CarVersionResource extends JsonResource
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
        'brand_id' => $this->car->brand_id,
        'brand_name' => $this->car->brand->name,
        'name' => $this->car->model_name,
        'varient_name' => $this->varient_name,
        'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
        'on_road_price' => 'KWD ' . $this->on_road_price,
        'finance_available' => 'KWD '. $this->finance_available,
        'image' => file_asset('files-car', $this->car->image),
    ];
  }
}
