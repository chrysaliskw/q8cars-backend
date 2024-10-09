<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteComparisonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'car_1' => $this->car1 ? [
                'car_id' => $this->car1->id,
                'image' => file_asset('files-favourite', $this->car1->image),
                'model_name' => $this->car1->model_name,
                'version_name' => $this->car1->carSpec->varient_name,
                'on_road_price' => "KWD " . $this->car1->on_road_price,
            ] : null,
            'car_2' => $this->car2 ? [
                'car_id' => $this->car2->id,
                'image' => file_asset('files-favourite', $this->car2->image),
                'model_name' => $this->car2->model_name,
                'version_name' => $this->car2->carSpec->varient_name,
                'on_road_price' => "KWD " . $this->car2->on_road_price,
            ] : null,
            'car_3' => $this->car3 ? [
                'car_id' => $this->car3->id,
                'image' => file_asset('files-favourite', $this->car3->image),
                'model_name' => $this->car3->model_name,
                'version_name' => $this->car3->carSpec->varient_name,
                'on_road_price' => "KWD " . $this->car3->on_road_price,
            ] : null,
            'car_4' => $this->car4 ? [
                'car_id' => $this->car4->id,
                'image' => file_asset('files-favourite', $this->car4->image),
                'model_name' => $this->car4->model_name,
                'version_name' => $this->car4->carSpec->varient_name,
                'on_road_price' => "KWD " . $this->car4->on_road_price,
            ] : null,
        ];
    }
}
