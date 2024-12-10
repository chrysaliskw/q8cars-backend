<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OfferResource extends JsonResource
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
    public function toArray($request)
    {
        $carVersion = $this->carVersion ?? $this->car->carSpec;
        return [
            'id' => $this->id,
            'car_name' => $this->car->model_name,
            'varient_name'=> $carVersion->varient_name  ,
            'title' => $this->title,
            'offer' => 'KWD '.$this->offer,
            'ex_showroom_price' => 'KWD ' . $carVersion->ex_showroom_price,
            'offer_price' => 'KWD ' . ((float)$carVersion->ex_showroom_price - (float)$this->offer),
            'time_left' => $this->TimeLeft($this->end_date),
            'image' => file_asset('files-car', $this->car->image) 
        ];
    }
    private function TimeLeft($endDate)
    {
        $endDate = Carbon::parse($endDate)->endOfDay();
        $currentDate = now();
        $diff = $currentDate->diff($endDate);
        $days = $diff->d; // Days
        $hours = $diff->h; // Hours
        $minutes = $diff->i; // Minutes
        $seconds = $diff->s; // Seconds

        if ($days > 0) {
            if ($days == 1) {
                return $days . " day left";
            } else {
                return $days . " days left";
            }
        } elseif ($hours > 0) {
            return ceil($hours) . " hours left";
        } elseif ($minutes > 0) {
            return $minutes . " minutes left";
        } elseif ($seconds > 0) {
            return $seconds . " seconds left";
        } else {
            return "Time has passed";
        }
    }  
}
