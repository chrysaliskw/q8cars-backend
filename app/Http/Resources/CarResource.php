<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\CarVersion;
use App\Models\Review;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

use function Laravel\Prompts\select;

class CarResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // public function toArray($request)
    // {

    //     return [
    //         'id' => $this->id,
    //         'brand_id' => $this->brand_id,
    //         'brand_name' => $this->brand->name,
    //         'name' => $this->model_name,
    //         // 'varient_name' => $this->version ? in_array($this->id, array_column($this->version, 'car_id')) ? $this->version->firstWhere('car_id', $this->id)->varient_name : null : null,
    //         'varient_name' => $request->version_id ? $this->getVarientName($request->version_id) : "NO",
    //         'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
    //         'on_road_price' => 'KWD ' . $this->on_road_price,
    //         'finance_available' => 'KWD ' . $this->finance_available,
    //         'rating' => $this->avg_rating,
    //         'total_reviews_count' => $this->total_reviews_count,
    //         'image' => file_asset('files-car', $this->image),
    //         'is_favourite' => $this->is_favourite,
    //         'image_2' => $this->image_2 ? file_asset('files-car', $this->image_2) : null,
    //         'added_date' => $this->formatDate($this->created_at),
    //     ];
    // }


    // public function toArray($request)
    // {
    //     // dd($request->all());
    //     $carIds = $request->carIds ?? [];
    //     // dd($carIds);
    //     $versionIds = $request->version_id ?? [];
    //     $response = [];

    //     foreach ($carIds as $index => $carId) {
    //         // Only include version data when there's a matching version_id at the same index
    //         $versionId = isset($versionIds[$index]) ? $versionIds[$index] : null;
    //         // dd($versionId);

    //         $carData = [
    //             'id' => $this->id,
    //             'brand_id' => $this->brand_id,
    //             'brand_name' => $this->brand->name,
    //             'name' => $this->model_name,
    //             'varient_name' => $versionId ? $this->getVarientName($versionId) : "NO",
    //             'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
    //             'on_road_price' => 'KWD ' . $this->on_road_price,
    //             'finance_available' => 'KWD ' . $this->finance_available,
    //             'rating' => $this->avg_rating,
    //             'total_reviews_count' => $this->total_reviews_count,
    //             'image' => file_asset('files-car', $this->image),
    //             'is_favourite' => $this->is_favourite,
    //             'image_2' => $this->image_2 ? file_asset('files-car', $this->image_2) : null,
    //             'added_date' => $this->formatDate($this->created_at),
    //         ];

    //         // Add single entry wrapped in array
    //         $response[] = [$carData];
    //     }
    //     // dd($response);
    //     return $response;
    // }

    public function toArray($request)
    {
        // Extract the carIds and versionIds from the request
        $carIds = $request->carIds ?? [];
        $versionIds = $request->version_id ?? [];

        $carsData = [];

        // Loop through the carIds array to process each car
        foreach ($carIds as $index => $carId) {
            // Check if the current car matches the carId
            if ($carId == $this->id) {
                // Determine the versionId corresponding to this carId
                $versionId = $versionIds[$index] ?? null;

                // Add car data to the result array
                $carsData[] = [
                    'id' => $this->id,
                    'brand_id' => $this->brand_id,
                    'brand_name' => $this->brand->name,
                    'name' => $this->model_name,
                    'varient_name' => $versionId ? $this->getVarientName($versionId) : "NO",
                    'ex_showroom_price' =>  $versionId ? 'KWD ' . $this->getExShowroomPrice($versionId) : "NO",
                    // 'ex_showroom_price' => 'KWD ' . $this->ex_showroom_price,
                    'on_road_price' => 'KWD ' . $this->on_road_price,
                    'finance_available' => 'KWD ' . $this->finance_available,
                    'rating' => $this->avg_rating,
                    // 'rating t' => $versionId ? $this->getRating($versionId) : "NO",
                    'total_reviews_count' => $this->total_reviews_count,
                    'image' => file_asset('files-car', $this->image),
                    'is_favourite' => $this->is_favourite,
                    'image_2' => $this->image_2 ? file_asset('files-car', $this->image_2) : null,
                    'added_date' => $this->formatDate($this->created_at),
                ];
            }
        }

        // Return all matching car data
        return $carsData;
    }








    // private function formatDate($createdAt)
    // {
    //     $date = Carbon::parse($createdAt);
    //     return $formattedDate =  $date->format('M Y');
    // }

    private function formatDate($createdAt)
    {
        $date = Carbon::parse($createdAt);
        return $formattedDate = $date->format('M Y');
    }


    // private function getVarientName($id)
    // {
    //     // logger($id . "NULL");
    //     $varient = CarVersion::select('varient_name')->whereIn('id', $id)->pluck('varient_name');
    //     $carVarient = Car::where('id', $this->id)->first()->carVersions->whereIn('id', $id)->first();
    //     if (!$carVarient) {
    //         return null;
    //     }
    //     return $carVarient->varient_name;
    // }
    private function getVarientName($versionId)
    {
        $carVarient = CarVersion::where('id', $versionId)
            ->where('car_id', $this->id)
            ->first();

        return $carVarient ? $carVarient->varient_name : "NO";
    }
    private function getExShowroomPrice($versionId)
    {
        $carVarient = CarVersion::where('id', $versionId)
            ->where('car_id', $this->id)
            ->first();

        return $carVarient ? $carVarient->ex_showroom_price : "NO";
    }
    private function getRating($versionId)
    {
        $carVarient = CarVersion::where('id', $versionId)
            ->where('car_id', $this->id)
            ->first();

        return $carVarient ? $carVarient->avg_rating : "NO";
    }
}
