<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\CarFavourite;
use App\Models\CarVersion;
use App\Models\Review;
use Faker\Core\Version;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OfferDetailsResource extends JsonResource
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
            'car_id' => $this->car_id,
            'car_name' => $this->car->model_name,
            'varient_name'=>  $carVersion->varient_name,
            'car_versions'=> $this->getVariants($this->car_id),
            'title' => $this->title,
            'key_feature_1' => $this->key_feature_1,
            'html_key_feature_1' => $this->html_key_feature_1,
            'html_key_feature_2' => $this->html_key_feature_2,
            'key_feature_2' => $this->key_feature_2,
            'icon_1'=> $this->key_icon_1 ? file_asset('files-offer', $this->key_icon_1) :'',
            'icon_2'=> $this->key_icon_2? file_asset('files-offer', $this->key_icon_2) :'',
            'content' => $this->description,
            'html_content' => $this->html_description,
            'rating' => $this->car->avg_rating,
            'total_reviews_count' => $this->car->total_reviews_count,
            'offer' => 'KWD '.$this->offer,
            'ex_showroom_price' => 'KWD ' .   $carVersion->ex_showroom_price,
            'offer_price' => 'KWD ' .  ( (float) $carVersion->ex_showroom_price -(float)$this->offer ),
            'time_span' => $this->TimeSpan($this->start_date ,$this->end_date),
            'image' => file_asset('files-car', $this->car->image),
            'is_favourite' => CarFavourite::where('user_id', Auth::id())->where('car_id',$this->car_id)->exists() ? 1: 0,
        ];
    }
    private function TimeSpan($startDate, $endDate)
    {
       
      $date1 = Carbon::parse($startDate); 
      $date2 = Carbon::parse($endDate); 
      return $date1->format('j M') . ' - ' . $date2->format('j M');
   
    }  
    private function getVariants($carId)
    {
        $car = Car::find($carId);
        $res = [];

        foreach ($car->carVersions as $version) {
            if ($version->status == CarVersion::STATUS_ACTIVE) {
                $res[] = [
                    'id' => $version->id,
                    'version_name' => $version->varient_name
                ];
            }
        }
        return $res;

    }
}
