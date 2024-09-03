<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class BrandResource extends JsonResource
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
            'name' => $this->name,
            'icon' => $this->icon ? file_asset('files-brand', $this->icon) : null, 
            'is_top_brand' => $this->is_top_brand,
            'is_recently_purchased' => $this->is_recent_purchased,
        ];
    }
}
