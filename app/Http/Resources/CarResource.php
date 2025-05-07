<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'key_id' => $this->id,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand->name,
            'name' => $this->model_name,
            'varient_name' => $this->varient,
            'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
            'on_road_price' => $this->on_road_price ?'KWD ' . $this->on_road_price :null,
            'finance_available' => $this->finance_available ?'KWD ' . $this->finance_available : null,
            'rating' => $this->avg_rating,
            'total_reviews_count' => $this->total_reviews_count,
            'image' => file_asset('files-car', $this->image),
            'is_favourite' => $this->is_favourite,
            'image_2' => $this->image_2 ? file_asset('files-car', $this->image_2) : null,
            'added_date' => $this->formatDate($this->created_at),
        ];
    }
    private function formatDate($createdAt)
    {
        $date = Carbon::parse($createdAt);
        return $formattedDate =  $date->format('M Y');
    }
}
