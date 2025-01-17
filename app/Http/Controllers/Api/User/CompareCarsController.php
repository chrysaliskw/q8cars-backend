<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\CarSuggestionResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\OfferResource;
use App\Models\Car;
use App\Models\News;
use App\Http\Resources\NewsResource;
use App\Models\CarComparisonList;
use App\Models\CarVersion;
use App\Models\BrandColorMapping;
use App\Http\Resources\CarVersionResource;
use App\Models\CarFavourite;
use App\Models\RecentComparison;
use Illuminate\Support\Facades\Auth;

class CompareCarsController extends ApiBaseController
{

    public function __invoke(Request $request)
    {
        $data['popular_cpomparisons'] = $this->getPopularComparison($request);
        $data['recently_launched_cars'] = $this->getrecentlyLaunchedCars($request);
        $data['fav_comparisons'] = $this->getFavouriteComparisons($request);
        $data['related_news'] = $this->getRelatedNews($request);
        return $this->success(['data' => $data], 'Compare Cars listing!', Response::HTTP_OK);
    }

    private function getPopularComparison($request)
    {
        $lists = CarComparisonList::where('status',1)->where('page', CarComparisonList::CAR_COMPARISON_PAGE)->when($request->body_type_id, function ($query, $value) {
            $query->where('body_type', $value);
        })->orderBy('view_count', 'Desc')->get();
        return $this->getComaprisonResult($lists);
    }
    private function getrecentlyLaunchedCars()
    {
        $recentlyLauchedCars = Car::where('is_just_launched', Car::JUST_LAUNCHED)
        ->active()
        ->orderByRaw('COALESCE(just_launch_sort_order) ASC')
        ->limit(4)
        ->get();
     $groupedLists = $recentlyLauchedCars->chunk(2)->toArray();
        return  $this->getGroupedComparison($groupedLists);
    }

    private function getFavouriteComparisons()
    {
        $fav = CarFavourite::where('user_id', Auth::id())->pluck('car_id');
        $cars = Car::whereIn('id', $fav)->orderBy('view_count', 'Desc')->get();
        $groupedLists = $cars->chunk(2)->toArray();
        return  $this->getGroupedComparison($groupedLists);
    }
    private function getGroupedComparison($lists)
    {
        $result = [];
        $i = 0;
        foreach ($lists as $group) {
            $group = array_values($group);
            if (count($group) == 2) {
                $car1 = Car::find($group[0]['id']);
                $car2 = Car::find($group[1]['id']);
                $result[$i] = [
                    'car_1' => $car1 ? new CarVersionResource($car1->carSpec) : [],
                    'car_2' => $car2 ? new CarVersionResource($car2->carSpec) : []
                ];
                $i++;
            }
        }
        return $result;
    }


    private function getRelatedNews()
    {
        $car1Ids = CarComparisonList::where('page', CarComparisonList::HOME_PAGE)->pluck('car_1_id')->toArray();
        $car2Ids = CarComparisonList::where('page', CarComparisonList::HOME_PAGE)->pluck('car_2_id')->toArray();
        $uniqueCarIds = array_unique(array_merge($car1Ids, $car2Ids));
        $result = News::active()->published()->whereIn('car_id', $uniqueCarIds)->limit(4)->orderBy('posted_time', 'asc')->get();
        if ($result) {
            return NewsResource::collection($result);
        } else {
            return [];
        }
    }

    private function getComaprisonResult($lists)
    {
        $result = [];

        $i = 0;
        foreach ($lists as $list) {
            $version1 = CarVersion::where('car_id', $list->car_1_id)
                ->where('is_car_spec', CarVersion::CAR_SPECIFICATION)
                ->first();
            if ($list->car_version_1_id) {
                logger($list->car_version_1_id);
                $version1 = CarVersion::find($list->car_version_1_id);
            }
            $car1 = $version1 ? new CarVersionResource($version1) : [];
            $version2 = CarVersion::where('car_id', $list->car_2_id)
                ->where('is_car_spec', CarVersion::CAR_SPECIFICATION)
                ->first();
            if ($list->car_version_2_id) {
                $version2 = CarVersion::find($list->car_version_2_id);
            }
            $car2 = $version2 ? new CarVersionResource($version2) : [];
            $result[] = [
                'car_1' => $car1 ?? [],
                'car_2' => $car2 ?? []
            ];

            $i++;
        }
        // dd($result);
        return $result;
    }

    public function getSuggestions()
    {
        $car1Ids = RecentComparison::where('user_id', Auth::id())->pluck('car_1_id')->toArray();
        $car2Ids = RecentComparison::where('user_id', Auth::id())->pluck('car_2_id')->toArray();
        $uniqueCarIds = array_unique(array_merge($car1Ids, $car2Ids));
        // $suggestions = Car::whereNotIn('id', $uniqueCarIds)->active()->orderBy('view_count')->limit(3)->get();
        $suggestions = Car::whereNotIn('id', $uniqueCarIds)->active()->orderByDesc('view_count')->launched()->limit(3)->get();
        return $this->success(['data' => CarSuggestionResource::collection($suggestions)], 'Compare Cars suggestions!', Response::HTTP_OK);
    }
}
