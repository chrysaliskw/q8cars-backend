<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\CarVersion;
use App\Models\Review;
use Faker\Core\Version;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
        return [
            'id' => $this->id,
            'car_id' => $this->car_id,
            'car_name' => $this->car->model_name,
            'varient_name'=> $this->carVersion->varient_name,
            'car_versions'=> $this->getVariants($this->car_id),
            'title' => $this->title,
            'key_feature_1' => $this->key_feature_1,
            'key_feature_2' => $this->key_feature_2,
            'icon_1'=> $this->icon ? file_asset('files-offer', $this->key_icon_1) :'',
            'icon_2'=> $this->icon ? file_asset('files-offer', $this->key_icon_2) :'',
            'content' => $this->description,
            'html_content' => $this->html_description,
            'rating' => $this->car->avg_rating,
            'total_reviews_count' => $this->car->total_reviews_count,
            'offer' => 'KWD '.$this->offer,
            'ex_showroom_price' => 'KWD ' . $this->carVersion->ex_showroom_price,
            'offer_price' => 'KWD ' .  ($this->carVersion->ex_showroom_price -$this->offer ),
            'time_span' => $this->TimeSpan($this->start_date ,$this->end_date),
            'image' => file_asset('files-car', $this->car->image) 
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
