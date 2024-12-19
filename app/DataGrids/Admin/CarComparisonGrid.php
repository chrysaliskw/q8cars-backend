<?php

namespace App\DataGrids\Admin;

use App\Models\CarComparisonList;
use Rufaidulk\DataGrid\Grid;

class CarComparisonGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {                                                                                       
        $query = CarComparisonList::query()
            ->leftJoin('cars as c', 'c.id', 'car_comparison_lists.car_id')
            ->leftJoin('cars as c1', 'c1.id', 'car_comparison_lists.car_1_id')
            ->leftJoin('cars as c2', 'c2.id', 'car_comparison_lists.car_2_id')

            ->leftJoin('brands as b', 'b.id', 'car_comparison_lists.brand_id')
            ->leftJoin('brands as b1', 'b1.id', 'car_comparison_lists.brand_1_id')
            ->leftJoin('brands as b2', 'b2.id', 'car_comparison_lists.brand_2_id')

            ->leftJoin('car_versions as v1', 'v1.id', 'car_comparison_lists.car_version_1_id')
            ->leftJoin('car_versions as v2', 'v2.id', 'car_comparison_lists.car_version_2_id')
            ->select([
                'c.id as car_model_id',
                'c.model_name as car_model_name',
                'c1.id as car_1_model_id',
                'c1.model_name as car_1_model_name',
                'c2.id as car_2_model_id',
                'c2.model_name as car_2_model_name',
                'v1.id as version_1_id',
                'v1.varient_name as version_1_name',
                'v2.id as version_2_id',
                'v2.varient_name as version_2_name',

                'b.id as brand_id',
                'b.name as brand_name',
                
                'b1.id as brand_1_id',
                'b1.name as brand_1_name',

                'b2.id as brand_2_id',
                'b2.name as brand_2_name',
                'car_comparison_lists.page as display_page',

                'car_comparison_lists.*'
            ])
            ->orderByDesc('car_comparison_lists.id');
            

        return $query;
    }

    public function columns()
    {

           return [
        'display_page' => [
            'label' => 'Page',
            'value' => function ($model) {
               return config('params.car-comparison-list.page')[$model->display_page];
            },
            'filter' => true,
            'filterOptions' => [
                'type' => 'select',
                'attribute' => 'car_comparison_lists.page',
                'operator' => '=',
                'data' => config('params.car-comparison-list.page')
            ],
            
        ],

            'brand_id' => [
                'label' => 'Brand',
                'value' => function ($model) {
                    return $model->brand_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'b.name',

                ],
                'sort'=>false,
            ],
           
            'car_id' => [
                'label' => 'Car Model',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c.model_name',
                ],
                'value' => function ($model) {
                    return $model->car_model_name;
                },
                'sort'=>false,

            ],

            'brand_1_id' => [
                'label' => 'Brand 1',
                'value' => function ($model) {
                    return $model->brand_1_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'b1.name',
                ],
                'sort'=>false,

            ],

            'car_1_id' => [
                'label' => 'Car Model 1',
              
                'value' => function ($model) {
                    return $model->car_1_model_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c1.model_name',
                ],
                'sort'=>false,

            ],

            'brand_2_id' => [
                'label' => 'Brand 2',
                'value' => function ($model) {
                    return $model->brand_2_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'b2.name',
                ],
                'sort'=>false,

            ],

            'car_2_id' => [
                'label' => 'Car Model 2',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c2.model_name',
                ],
                'value' => function ($model) {
                    return $model->car_2_model_name;
                },
                'sort'=>false,

            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'car_comparison_lists.status',
                    'operator' => '=',
                    'data' => config('params.car.status')
                ],
                'value' => function ($model) {
                    return config('params.car.status')[$model->status];
                },
                'contentCssClass' => 'filter',
            ],
            'action' => [
                'routePrefix' => 'admin.comparison',
                'contentCssClass' => 'grid-action-col',
            ]


        ];
    }
}
