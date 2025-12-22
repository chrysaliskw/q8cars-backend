<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class BannerResource extends JsonResource
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
            'file_name' => $this->file_name ? file_asset('files-banner', $this->file_name) : null,
            'file_name_mobile_view' => $this->file_name_mobile_view ? file_asset('files-banner', $this->file_name_mobile_view) : null,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];
    }
}
