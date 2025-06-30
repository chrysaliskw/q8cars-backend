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
//   public function toArray($request)
//   {
//     $fuelType = $this->fuel_type;
//     $engineUnit = in_array($fuelType, [1, 2, 3, 5]) ? 'L' : ($fuelType == 4 ? 'kW' : 'cc');
//     $mileageUnit = ($fuelType == 4) ? 'kWh' : 'kmpl';

//     return [
//         'id' => $this->id,
//         'version_name' => $this->varient_name,
//         'transmission_type' => config('params.car.transmission_type')[$this->transmission_type],
//         'fuel_type' => config('params.car.fuel_type')[$this->fuel_type],
//         // 'engine_capacity' => $this->engine_capacity. ' cc',
//         // 'mileage' => $this->mileage ? ($this->fuel_type == 4 ? $this->mileage. ' kwh':$this->mileage. ' kmpl') :'',
//         'engine_capacity' => $this->engine_capacity
//             ? $this->engine_capacity . ' ' . $engineUnit
//             : '',
//         'mileage' => $this->mileage
//             ? $this->mileage . ' ' . $mileageUnit
//             : '',
//         'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
//         'on_road_price' =>  $this->on_road_price ? 'KWD ' . $this->on_road_price :'',
//         'price_in_lakh' => 'KWD '. $this->ex_showroom_price,
//         // 'price_in_lakh' => 'KWD '. $this->priceLack($this->ex_showroom_price).' Lakh',
//     ];
//   }

    public function toArray($request)
    {
        $fuelType = $this->fuel_type;

        // $engineUnit = in_array($fuelType, [1, 2, 3, 5]) ? 'L' : ($fuelType == 4 ? 'kW' : 'cc');
        $engineUnit = match ($fuelType) {
            4 => 'kW',
            5 => 'L/kW',
            1, 2, 3 => 'L',
            default => 'cc',
        };

        $mileageUnit = ($fuelType == 4) ? 'kWh' : 'kmpl';

        $response = [
            'id' => $this->id,
            'version_name' => $this->varient_name,
            'transmission_type' => config('params.car.transmission_type')[$this->transmission_type] ?? 'N/A',
            'fuel_type' => config('params.car.fuel_type')[$fuelType] ?? 'N/A',
            'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
            'on_road_price' => $this->on_road_price ? 'KWD ' . $this->on_road_price : '',
            'price_in_lakh' => 'KWD ' . $this->ex_showroom_price,
            'engine_unit' => $engineUnit
        ];

        if ($this->engine_capacity) {
            $response['engine_capacity'] = $this->engine_capacity . ' ' . $engineUnit;
        }

        if ($this->mileage) {
            $response['mileage'] = $this->mileage . ' ' . $mileageUnit;
        }

        return $response;
    }


  private function priceLack($amount)
  {
    $lakhs = $amount / 100000;

    // Format to 2 decimal points
    return number_format($lakhs, 2);

  }
}
