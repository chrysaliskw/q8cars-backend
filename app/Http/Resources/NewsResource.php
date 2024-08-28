<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'car_id' => $this->car_id,
            'car_name' => $this->car->name,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand ? $this->brand->name : null,
            'car_version_id' => $this->car_version_id,
            'car_version_name' => $this->carVersion ? $this->carVersion->version_name : null,
            'title' => $this->title,
            'content' => $this->content,
            'html_content' => $this->html_content,
            'posted_date' => date('d M, YY', strtotime($this->posted_time)),
            'posted_time' => dateTimeFormat($this->posted_time),
            'is_published' => $this->is_published,
            'status' => $this->status,
            // 'status_text' => config('params.news.status')[$this->status],
            'read_time' => $this->read_time,
            'expiry_date' => dateFormat($this->expiry_date),
            'show_in_detail_page' => $this->show_in_detail_page,
            'media_logo' => file_asset('files-news', $this->media_logo),
            'image' => file_asset('files-news', $this->image),
            'posted_time_ago' => get_time_ago($this->posted_time),
        ];
    }
}   