<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CarSuggestionResource extends JsonResource
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
        'varient_name'=> $this->carSpec->varient_name,
        'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
        'on_road_price' =>$this->on_road_price ? 'KWD ' . $this->on_road_price :null,
        'image' => file_asset('files-car', $this->image),
        'transmission_type' => config('params.car.transmission_type')[$this->carSpec->transmission_type],
        'fuel_type' => config('params.car.fuel_type')[$this->carSpec->fuel_type]  ,
        'rating' => $this->avg_rating,   
    ];
  }
  
}
