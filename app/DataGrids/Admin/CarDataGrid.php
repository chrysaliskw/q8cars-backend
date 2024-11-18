<?php

namespace App\DataGrids\Admin;

use App\Models\Car;
use Rufaidulk\DataGrid\Grid;

class CarDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Car::query()->orderBy('id','Desc')
            ->leftJoin('brands as b', 'b.id', 'cars.brand_id')
            ->select(['b.id as brand_id', 'b.name as brand_name','cars.*']);

        return $query;
    }

    public function columns()
    {
        return [
            'image' => [
                'label' => 'Image',
                'filter' => false,
                'sort' => false,
                'value' => function ($model) {
                    if ($model->image) {
                        $url = file_asset('files-car', $model->image);
                        return "<img src='{$url}' alt='car-img' class='img-thumbnail img-list'>";
                    } 
                }
            ],
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

            'view_count' => [
                'label' => 'View Count',
                'value' => function ($model) {
                    return $model->view_count;
                },
                'filter' => false,
                // 'filterOptions' => [
                //     'type' => 'text',
                //     'attribute' => 'view_count',
                // ]
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

            'action' => [
                'routePrefix' => 'admin.car',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
