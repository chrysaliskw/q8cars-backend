<?php

namespace App\Services\Api\User\Car;

use App\Models\Car;
use App\Models\CarFavourite;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

final class FilterService
{
    const SORT_BY_VIEW_COUNT = 1;
    const SORT_BY_LATEST = 2;
    const SORT_BY_PRICE_LOW_TO_HIGH = 3;
    const SORT_BY_PRICE_HIGH_TO_LOW = 4;
    const SORT_BY_MILEAGE_LOW_TO_HIGH = 5;
    const SORT_BY_MILEAGE_HIGH_TO_LOW = 6;
    const SORT_BY_SAFETY_RATINGS_LOW_TO_HIGH = 7;
    const SORT_BY_SAFETY_RATINGS_HIGH_TO_LOW = 8;
    const SORT_BY_LIKE_COUNT = 9;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var int
     */
    private $sortBy;

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
        $this->sortBy = $request->sort;
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
        $this->filterByBrand();
        $this->filterByBodyType();
        $this->filterByFuelType();
        $this->filterByTransmissionType();
        $this->filterByBudget();
        $this->filterBySeatCapacity();
        $this->filterByAirBags();
        $this->filterByMileage();
        $this->filterBySafetyRatings();
        $this->filterByEngineCapacity();
        $this->filterByPower();
        $this->filterByTorque();
        $this->filterByColors();
        $this->filterByProfession();
        $this->applySorting();

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
                       'cars.brand_id','model_name',
                       'cars.ex_showroom_price', 'cars.on_road_price', 'cars.finance_available',
                       'avg_rating', 'total_reviews_count',
                       'image'
                    ])
                    ->selectRaw('IF(cf.id IS NULL, 0, 1) as is_favourite')
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
        $this->query = Car::active()
                        ->with('brand')
                        ->leftJoin('car_favourites AS cf', function ($join) {
                            $join->on('cf.car_id', '=', 'cars.id')
                                ->where('cf.user_id', Auth::id());

                        });
    }

    /**
     * @return void
     */
    private function filterByBrand()
    {
        if (! $this->request->brand_id) {
            return;
        }

        $this->query = $this->query->whereIn('cars.brand_id', $this->request->brand_id);
    }

     /**
     * @return void
     */
    private function filterByBodyType()
    {
        if (! $this->request->body_type_id) {
            return;
        }

        $ids = CarVersion::whereIn('body_type', $this->request->body_type_id)->pluck('car_id')->toArray();

        $this->query = $this->query->whereIn('cars.id', $ids);
    }

    /**
     * @return void
     */
    private function filterByFuelType()
    {
        if (! $this->request->fuel_types) {
            return;
        }

        $fuel_types = array_map('intval', $this->request->fuel_types);

        $this->query = $this->query->whereJsonContains('cars.fuel_types', $fuel_types);


        // $ids = CarVersion::whereIn('fuel_type', $this->request->fuel_types)->pluck('car_id')->toArray();
        // $this->query = $this->query->whereIn('cars.id', $ids);

    }

    /**
     * @return void
     */
    private function filterByProfession()
    {
        if (! $this->request->professions) {
            return;
        }
        $professions = array_map('intval', $this->request->professions);

        $this->query = $this->query->whereJsonContains('cars.professions', $professions);

        // $ids = CarVersion::whereIn('transmission_type', $this->request->transmission_types)->pluck('car_id')->toArray();
        // $this->query = $this->query->whereIn('cars.id', $ids);
    }

     /**
     * @return void
     */
    private function filterByBudget()
    {
        if (is_null($this->request->min_price) || is_null($this->request->max_price)) {
            return;
        }
        // $ids = CarVersion::whereBetween('finance_available', [$this->request->min_price, $this->request->max_price])
        //         ->pluck('car_id')
        //         ->toArray();
        // $this->query = $this->query->whereIn('cars.id', $ids);
        $this->query = $this->query->whereBetween('cars.on_road_price', [
                $this->request->min_price, $this->request->max_price
        ]);
    }

     /**
     * @return void
     */
    private function filterByTransmissionType()
    {
        if (! $this->request->transmission_types) {
            return;
        }
        $transmission_types = array_map('intval', $this->request->transmission_types);

        $this->query = $this->query->whereJsonContains('cars.transmission_types', $transmission_types);

        // $ids = CarVersion::whereIn('transmission_type', $this->request->transmission_types)->pluck('car_id')->toArray();
        // $this->query = $this->query->whereIn('cars.id', $ids);
    }


    /**
     * @return void
     */
    private function filterBySeatCapacity()
    {
        if (! $this->request->seat_capacity) {
            return;
        }

        $this->query = $this->query->whereIn('cars.seat_capacity', $this->request->seat_capacity);

    }

     /**
     * @return void
     */
    private function filterByAirBags()
    {
        if (! $this->request->no_of_airbags_min) {
            return;
        }
        // $ids = CarVersion::whereIn('no_of_airbags', $this->request->no_of_airbags)->pluck('car_id')->toArray();
        // $this->query = $this->query->whereIn('cars.id', $ids);
        $ids = [];
        for($i = 0; $i < count($this->request->no_of_airbags_min); $i++) {
            if($this->request->no_of_airbags_max[$i] == 0) {
                $ids = array_merge($ids, CarVersion::where('no_of_airbags','>=', $this->request->no_of_airbags_min[$i])->pluck('car_id')->toArray());
            }else {
                $ids = array_merge($ids, CarVersion::whereBetween('no_of_airbags',
                    [$this->request->no_of_airbags_min[$i], $this->request->no_of_airbags_max[$i]])->pluck('car_id')->toArray());
            }
        }
        $this->query = $this->query->whereIn('cars.id', $ids);

    }

    /**
     * @return void
     */
    private function filterBySafetyRatings()
    {
        if (! $this->request->safety_ratings) {
            return;
        }

        $this->query = $this->query->whereIn('cars.safety_ratings', $this->request->safety_ratings);
    }

     /**
     * @return void
     */
    private function filterByTorque()
    {
        if (! $this->request->torque_min) {
            return;
        }

        $ids = [];

        for($i = 0; $i < count($this->request->torque_min); $i++) {
            if($this->request->torque_max[$i] == 0) {
                $ids = array_merge($ids, CarVersion::where('torque','>=', $this->request->torque_min[$i])->pluck('car_id')->toArray());
            }else {
                $ids = array_merge($ids, CarVersion::whereBetween('torque',
                    [$this->request->torque_min[$i], $this->request->torque_max[$i]])->pluck('car_id')->toArray());
            }
        }
        $this->query = $this->query->whereIn('cars.id', $ids);
        //$this->query = $this->query->where('cars.mileage', $this->request->mileage);

    }
      /**
     * @return void
     */
    private function filterByPower()
    {
        if (! $this->request->power_min) {
            return;
        }

        $ids = [];

        for($i = 0; $i < count($this->request->power_min); $i++) {
            if($this->request->power_max[$i] == 0) {
                $ids = array_merge($ids, CarVersion::where('power','>=', $this->request->power_min[$i])->pluck('car_id')->toArray());
            }else {
                $ids = array_merge($ids, CarVersion::whereBetween('power',
                    [$this->request->power_min[$i], $this->request->power_max[$i]])->pluck('car_id')->toArray());
            }
        }
        $this->query = $this->query->whereIn('cars.id', $ids);
        //$this->query = $this->query->where('cars.mileage', $this->request->mileage);

    }
     /**
     * @return void
     */
    private function filterByEngineCapacity()
    {
        if (! $this->request->engine_capacity_min) {
            return;
        }

        $ids = [];

        for($i = 0; $i < count($this->request->engine_capacity_min); $i++) {
            if($this->request->engine_capacity_max[$i] == 0) {
                $ids = array_merge($ids, CarVersion::where('engine_capacity','>=', $this->request->engine_capacity_min[$i])->pluck('car_id')->toArray());
            }else {
                $ids = array_merge($ids, CarVersion::whereBetween('engine_capacity',
                    [$this->request->engine_capacity_min[$i], $this->request->engine_capacity_max[$i]])->pluck('car_id')->toArray());
            }
        }
        $this->query = $this->query->whereIn('cars.id', $ids);
        //$this->query = $this->query->where('cars.mileage', $this->request->mileage);

    }
     /**
     * @return void
     */
    private function filterByMileage()
    {
        if (! $this->request->mileage_min) {
            return;
        }

        $ids = [];

        for($i = 0; $i < count($this->request->mileage_min); $i++) {
            if($this->request->mileage_max[$i] == 0) {
                $ids = array_merge($ids, CarVersion::where('mileage','>=', $this->request->mileage_min[$i])->pluck('car_id')->toArray());
            }else {
                $ids = array_merge($ids, CarVersion::whereBetween('mileage',
                    [$this->request->mileage_min[$i], $this->request->mileage_max[$i]])->pluck('car_id')->toArray());
            }
        }
        $this->query = $this->query->whereIn('cars.id', $ids);
        //$this->query = $this->query->where('cars.mileage', $this->request->mileage);

    }
    /**
     * @return void
     */
    private function filterByColors()
    {
        if (! $this->request->colours) {
            return;
        }

        $colours = array_map('intval', $this->request->colours); // Replace with your actual array

        $this->query = $this->query->whereJsonContains('cars.colours', $colours);
    }


    /**
     * @return void
     */
    private function applySorting()
    {
        switch ($this->sortBy)
        {
            case self::SORT_BY_VIEW_COUNT:
                $this->query = $this->query->orderBy('cars.view_count', 'desc');
                break;

            case self::SORT_BY_LATEST:
                $this->query = $this->query->orderBy('cars.id', 'desc');
                break;

            case self::SORT_BY_LIKE_COUNT:
                    $this->query = $this->query->orderBy('cars.favourites_count', 'desc');
                    break;

            case self::SORT_BY_PRICE_LOW_TO_HIGH:
                $this->query = $this->query->orderBy('cars.on_road_price', 'asc');
                break;

            case self::SORT_BY_PRICE_HIGH_TO_LOW:
                $this->query = $this->query->orderBy('cars.on_road_price', 'desc');
                break;

            case self::SORT_BY_MILEAGE_LOW_TO_HIGH:
                    $this->query = $this->query->orderBy('cars.mileage', 'asc');
                    break;

            case self::SORT_BY_MILEAGE_HIGH_TO_LOW:
                    $this->query = $this->query->orderBy('cars.mileage', 'desc');
                    break;

            case self::SORT_BY_SAFETY_RATINGS_LOW_TO_HIGH:
                        $this->query = $this->query->orderBy('cars.safety_ratings', 'asc');
                        break;

            case self::SORT_BY_SAFETY_RATINGS_HIGH_TO_LOW:
                        $this->query = $this->query->orderBy('cars.safety_ratings', 'desc');
                        break;

            default:
                $this->query = $this->query->orderBy('cars.sort_order', 'asc');
                break;

        }
    }
}
