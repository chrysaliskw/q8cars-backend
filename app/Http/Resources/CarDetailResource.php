<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class CarDetailResource extends JsonResource
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
        'version_name' => $this->varient_name,
        'transmission_type' => config('params.car.transmission_type')[$this->transmission_type],  
        'fuel_type' => config('params.car.fuel_type')[$this->fuel_type],
        'engine_capacity' => $this->engine_capacity. ' cc',
        'mileage' => $this->mileage. ' kmpl',
        'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
        'on_road_price' => 'KWD ' . $this->on_road_price,
        'price_in_lakh' => 'KWD '. $this->ex_showroom_price,
        // 'price_in_lakh' => 'KWD '. $this->priceLack($this->ex_showroom_price).' Lakh',
    ];
  }

  private function priceLack($amount)
  {
    $lakhs = $amount / 100000;

    // Format to 2 decimal points
    return number_format($lakhs, 2);

  }
}
