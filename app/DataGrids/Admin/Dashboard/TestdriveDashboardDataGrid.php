<?php

namespace App\DataGrids\Admin\Dashboard;

use App\Models\TestDrive;
use Illuminate\Support\Facades\Auth;
use Rufaidulk\DataGrid\Grid;

class TestdriveDashboardDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public $pageSize = 5;

    public function renderPaginationLinks()
    {
        return null;
    }

    public function gridQuery()
    {
        $query = TestDrive::query()
            ->leftJoin('users as u', 'u.id', '=', 'test_drives.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'test_drives.car_id')
            ->leftJoin('brands', 'brands.id', '=', 'c.brand_id')
            ->select(['test_drives.*', 'u.phone_code as user_phone_code', 'u.mobile as user_mobile', 'c.model_name as car_model', 'brands.name as brand_name'])
            ->orderBy('test_drives.id', 'Desc');
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
                    return $model->first_name . ' ' . $model->last_name;
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
                    'data' => config('params.test_drive.status')
                ],
                'value' => function ($model) {
                    return config('params.test_drive.status')[$model->status] ?? 'Unknown';
                },
            ],
            'action' => [
                'routePrefix' => 'admin.test-ride-requests',
                'buttons' => ['view'],
                // 'view' => function ($model) {
                //     if (Auth::user()->can('Users') || Auth::user()->can('Dashboard')) {
                //         return "<a href='" . route('admin.loan-requests.show', $model->id) . "' class='btn btn-primary btn-sm' >View</a>";
                //     }
                // },
            ]
        ];
    }
}
