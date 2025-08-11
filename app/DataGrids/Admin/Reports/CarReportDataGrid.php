<?php

namespace App\DataGrids\Admin\Reports;

use App\Models\BankSuggestionRequest;
use App\Models\Car;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarReportDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {

        $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

        $query = Car::query()
            ->select([
                'b.id as brand_id',
                'b.name as brand_name',
                'cars.id',
                'cars.car_ref_no',
                'cars.model_name as model_name',
                'cars.sort_order as sort_order',
                'cars.status as status',
                'cars.is_upcoming as is_upcoming',
                'cars.is_just_launched as is_just_launched',
                DB::raw('(SELECT COUNT(*) FROM car_versions WHERE car_versions.car_id = cars.id AND car_versions.status = 1) as version_count'),
                DB::raw('(SELECT COUNT(*) FROM test_drives WHERE test_drives.car_id = cars.id AND test_drives.status = 3) as test_ride_count'),
                DB::raw('(SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 1) as offer_request_count'),
                DB::raw('(SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 2) as onroad_price_count'),
                DB::raw('(SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 3) as emi_request_count'),

            ])
            ->leftJoin('brands as b', 'b.id', '=', 'cars.brand_id') // Ensure correct join
            ->groupBy('cars.id', 'b.id', 'b.name', 'cars.model_name', 'cars.sort_order', 'cars.status') // Group by necessary columns
            ->orderBy('cars.id', 'DESC'); // Sort by car ID in descending order
        $query->when(request()->query('car_1_id'), function ($query, $model) {
            $query->where('cars.id', request()->query('car_1_id'));
        });
        $query->when(request()->query('brand_1_id'), function ($query, $brand) {
            $query->where('cars.brand_id', request()->query('brand_1_id'));
        });
        $query->when(request()->query('is_upcoming'), function ($query, $value) {
            $query->where('cars.is_upcoming', $value);
        });
        $query->when(request()->query('is_just_launched'), function ($query, $value) {
            $query->where('cars.is_just_launched', $value);
        });
        $query->when(request()->query('start_date'), function ($q) use ($startDate, $endDate) {
            $q->where('cars.created_at', '>=', $startDate)
                ->where('cars.created_at', '<=', $endDate);
        });

        return $query;
    }

    public function columns()
    {
        return [

            'model_name' => [
                'label' => 'Model Name',
                'value' => function ($model) {
                    return $model->model_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'model_name',
                ]
            ],
            'brand_name' => [
                'label' => 'Brand',
                'value' => function ($model) {
                    return $model->brand_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'b.name',
                ]
            ],
            'car_ref_no' => [
                'label' => 'Reference No.',
                'value' => function ($model) {
                    return $model->car_ref_no;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'car_ref_no',
                ]
            ],
            'sort_order' => [
                'label' => 'Sort Order',
                'value' => function ($model) {
                    return $model->sort_order;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'sort_order',
                ]
            ],
            'version_count' => [
                'label' => 'Version Count',
                'value' => function ($model) {
                    return $model->version_count;
                },
                'filter' => false,
            ],
            'is_upcoming' => [
                'label' => 'Is Upcoming',
                'value' => function ($model) {
                    return ($model->is_upcoming == 1) ? 'Yes' : 'No';
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'cars.is_upcoming',
                    'operator' => '=',
                    'data' => $this->getUpcoming(),
                ],
            ],
            'is_just_launched' => [
                'label' => 'Is Just Launched',
                'value' => function ($model) {
                    return ($model->is_just_launched == 1) ? 'Yes' : 'No';
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'cars.is_just_launched',
                    'operator' => '=',
                    'data' => $this->getUpcoming(),
                ],
            ],
            'test_ride_count' => [
                'label' => 'Test Ride Count',
                'value' => function ($model) {
                    return $model->test_ride_count;
                },
                'filter' => false,
            ],
            'offer_request_count' => [
                'label' => 'Offer Request Count',
                'value' => function ($model) {
                    return $model->offer_request_count;
                },
                'filter' => false,
            ],
            'onroad_price_count' => [
                'label' => 'On Road Price Request',
                'value' => function ($model) {
                    return $model->onroad_price_count;
                },
                'filter' => false,
            ],
            'emi_request_count' => [
                'label' => 'Emi Request Count',
                'value' => function ($model) {
                    return $model->emi_request_count;
                },
                'filter' => false,
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'cars.status',
                    'operator' => '=',
                    'data' => config('params.car.status')
                ],
                'value' => function ($model) {
                    return config('params.car.status')[$model->status];
                },
                'contentCssClass' => 'filter',
            ],

        ];
    }

    private function getUpcoming()
    {
        return [
            1 => 'Yes',
            2 => 'No',
        ];
    }
}
