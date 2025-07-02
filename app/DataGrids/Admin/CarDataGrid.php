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
                    'data' => $this->getRecentlyLaunched(),
                ],
            ],
            'just_launch_sort_order' => [
                'label' => 'Just Launch Sort Order',
                'value' => function ($model) {
                    return $model->just_launch_sort_order;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'just_launch_sort_order',
                ]
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
    private function getUpcoming()
    {
        return [
            1 => 'Yes',
            2 => 'No',
        ];
    }
    private function getRecentlyLaunched()
    {
        return [
            1 => 'Yes',
            2 => 'No',
        ];
    }

}
