<?php

namespace App\DataGrids\Admin\Reports;

use App\Models\TestDrive;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestRideReportDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
{
    $endDate = Carbon::parse(request()->query('end_date'))
        ->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
    $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

    $query = TestDrive::query()
    ->leftJoin('users as u', 'u.id', '=', 'test_drives.user_id')
    ->leftJoin('cars as c', 'c.id', '=', 'test_drives.car_id')
    ->leftJoin('brands', 'brands.id', '=', 'c.brand_id')
    ->where('test_drives.status','!=',TestDrive::STATUS_NOT_VERIFIED)
    
    ->when(request()->query('brand_1_id'), function ($q) {
        $q->where('c.brand_id', request()->query('brand_1_id'));
    })
    ->when(request()->query('car_1_id'), function ($q) {
        $q->where('test_drives.car_id', request()->query('car_1_id'));
    })
    ->select(['test_drives.*','u.phone_code as user_phone_code','u.mobile as user_mobile','c.model_name as car_model','brands.name as brand_name'])
    ->where('test_drives.status', '<>', 5)
    ->orderBy('test_drives.id', 'Desc');
    
    $query->when(request()->query('start_date'), function ($q) use ($startDate, $endDate) {
        $q->whereBetween('test_drives.created_at', [$startDate, $endDate]);
    });

    return $query;
}


    public function columns()
    {
        return [
           
            'user_mobile' => [
                'label' => 'User Mobile',
                'value' => function ($model) {
                    return "<a href='" . route('admin.user.show', $model->user_id) . "'> {$model->user_phone_code} {$model->user_mobile}</a>";
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'u.mobile',
                ]
            ],
            'car_model' => [
                'label' => 'Car Model',
                'value' => function ($model) {
                    return $model->car_model;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c.model_name',
                ]
            ],
            'brand_name' => [
                'label' => 'Car Brand',
                'value' => function ($model) {
                    return $model->car && $model->car->brand
                        ? "<a href='" . route('admin.brand.show', $model->car->brand->id) . "'>{$model->car->brand->name}</a>"
                        : 'N/A';
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'brands.name',
                ]
            ],
            'first_name' => [
                'label' => 'Requested Name',
                'value' => function ($model) {
                    return $model->first_name . ' '. $model->last_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'first_name',
                ],
            ],
            'mobile' => [
                'label' => 'Requested Mobile',
                'value' => function ($model) {
                    return $model->phone_code . $model->mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'operator' => '=',
                    'attribute' => 'test_drives.mobile',
                ]
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'test_drives.status',
                    'operator' => '=',
                    'data' => $this->getStatus(),
                ],
                'value' => function ($model) {
                    return config('params.test_drive.status')[$model->status] ?? 'Unknown';
                },
            ],
            // 'email' => [
            //     'label' => 'Email',
            //     'value' => function ($model) {
            //         return $model->email;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'type' => 'text',
            //         'attribute' => 'email',
            //     ]
            // ],

            // 'status' => [
            //     'label' => 'Status',
            //     'filter' => true,
            //     'filterOptions' => [
            //         'type' => 'select',
            //         'attribute' => 'users.status',
            //         'operator' => '=',
            //         'data' => config('params.user.status')
            //     ],
            //     'value' => function ($model) {
            //         return config('params.user.status')[$model->status];
            //     },
            //    'contentCssClass' => 'filter',
            // ],
            'created_at' => [
                'label' => 'Created Date',
                'value' => function ($model) {
                    return dateFormat($model->created_at);
                },
                'filter' => false,
            ],

        ];
    }
    private function getStatus()
    {
        $value = config('params.test_drive.status');
        unset($value[5]);
        return $value;
    }
}
