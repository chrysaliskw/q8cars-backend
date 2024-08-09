<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqListResource extends JsonResource
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
        'model_name' => $this->car->model_name,
        'brand_id' => $this->car->brand_id,
        'brand_name' => $this->car->brand->name,
        'question' => $this->question,
        'answer' => $this->answer,
        'answer_status' =>$this->answer_status,
    ];
  }
}
