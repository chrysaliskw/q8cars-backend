<?php

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class CuratedComparisonResource extends JsonResource
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
        'image_1' =>  file_asset('files-curated_comparisons', $this->image_1), 
        'content' => $this->content,
        'html_content' => $this->html_content,
        'title' => $this->title,
        'status' => $this->status,
        'source' => $this->source,
        'image_2' =>   $this->image_2 ? file_asset('files-curated_comparisons', $this->image_2) : '', 
        'image_3' =>  $this->image_3 ? file_asset('files-curated_comparisons', $this->image_3) : '', 
        'published_at' => get_time_ago($this->published_date),
    ];
  }
}
