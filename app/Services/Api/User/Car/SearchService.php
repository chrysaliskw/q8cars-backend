<?php

namespace App\Services\Api\User\Car;

use App\Models\Car;
use App\Models\CarFavourite;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

final class SearchService
{


    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var int
     */
    private $search;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $query;

    /**
     * @var array
     */
    private $ids;

    /**
     * Creates a new instance
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->search = $request->search;
        $this->request = $request;
    }

    /**
     * Handles the filter
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function handle()
    {
        $this->setQuery();
        $this->searchByBrand();
        $this->searchByBodyType();
        $this->searchByBudget();
        $this->searchBySeatCapacity();
        $this->searchByKeyword();
        $this->searchByTravelType();
        return $this->getResultData();
    }
    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getResultData()
    {
        $result =  $this->query
                        ->select([
                            'cars.id',
                            'cars.brand_id',
                            'model_name',
                            // 'car_versions.varient_name as varient',
                            DB::raw('MAX(cars.ex_showroom_price) as ex_showroom_price'),
                            DB::raw('MAX(cars.on_road_price) as on_road_price'),
                            DB::raw('MAX(cars.finance_available) as finance_available'),
                            DB::raw('MAX(avg_rating) as avg_rating'),
                            DB::raw('MAX(total_reviews_count) as total_reviews_count'),
                            DB::raw('MAX(image) as image'),
                            DB::raw('MAX(image_2) as image_2'),
                            DB::raw('MAX(car_versions.varient_name) as varient'),
                            DB::raw('IF(MAX(cf.id) IS NULL, 0, 1) as is_favourite') // Using MAX to resolve the conflict
                        ])
                        ->groupBy('cars.id') // Ensure each car_id appears only once
                        ->paginate(20);

        return $result;
    }



    /**
     * Sets the main query
     *
     * @return void
     */
    private function setQuery()
    {
        $this->query = Car::where('cars.status',Car::STATUS_ACTIVE)
         ->launched()
                        // ->with('brand','carVersions')
                        ->leftJoin('car_favourites AS cf', function ($join) {
                            $join->on('cf.car_id', '=', 'cars.id')
                                 ->where('cf.user_id', Auth::id());
                        })
                        ->leftJoin('brands', 'brands.id', '=', 'cars.brand_id') // Joining the brands table
                        ->Join('car_versions', 'car_versions.car_id', '=', 'cars.id'); // Joining the car_variants table


    }

    /**
     * @return void
     */
    private function searchByBrand()
    {
        if (! $this->request->brand_id) {
            return;
        }

        $this->query = $this->query->where('cars.brand_id', $this->request->brand_id);

    }

     /**
     * @return void
     */
    private function searchByBodyType()
    {
        if (! $this->request->body_type_id) {
            return;
        }

        $ids = CarVersion::where('body_type', $this->request->body_type_id)->pluck('car_id')->toArray();

        $this->query = $this->query->whereIn('cars.id', $ids);
    }



     /**
     * @return void
     */
    private function searchByBudget()
    {
        if (is_null($this->request->min_price) || is_null($this->request->max_price)) {
            return;
        }
        $this->query = $this->query->whereBetween('cars.ex_showroom_price', [
                $this->request->min_price, $this->request->max_price
        ]);
    }


    /**
     * @return void
     */
    private function searchBySeatCapacity()
    {
        if (! $this->request->seat_capacity) {
            return;
        }

        $this->query = $this->query->where('cars.seat_capacity', $this->request->seat_capacity);

    }
    private function searchByTravelType()
    {
        if (! $this->request->travel_type) {
            return;
        }

        $this->query = $this->query->whereIn('car_versions.travel_type', $this->request->travel_type);

    }
    private function searchByKeyword()
    {
        if (! $this->request->search) {
            return;
        }

        $searchTerm = $this->request->search;

        $this->query = $this->query->where(function ($query) use ($searchTerm) {
            $query->where('cars.model_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('brands.name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('car_versions.varient_name', 'LIKE', '%' . $searchTerm . '%'); // Searching car_variant name
        });
    }



}
