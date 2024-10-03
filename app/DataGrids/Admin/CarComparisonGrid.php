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
                'car_comparison_lists.*'
            ])
            ->orderByDesc('car_comparison_lists.id');

        return $query;
    }

    public function columns()
    {

           return [
        'page' => [
            'label' => 'Page',
            'value' => function ($model) {
                // Determine the page based on the model's 'page' property
                if ($model->page == 1) {
                    return 'Home Page';
                } elseif ($model->page == 2) {
                    return 'Detailed Page';
                } else {
                    return 'Unknown Page'; // Handle other cases if necessary
                }
            }
        ],
           
            'car_id' => [
                'label' => 'Car Model',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c.id',
                ],
                'value' => function ($model) {
                    return $model->car_model_name;
                },
            ],

            'car_1_id' => [
                'label' => 'Car Model 1',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c2.id',
                ],
                'value' => function ($model) {
                    return $model->car_1_model_name;
                },
            ],
            'car_version_1_id ' => [
                'label' => 'Car Version 1',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'v1.id',
                ],
                'value' => function ($model) {
                    return $model->version_1_name ;
                },
            ],
            'car_2_id' => [
                'label' => 'Car Model 2',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c2.id',
                ],
                'value' => function ($model) {
                    return $model->car_2_model_name;
                },
            ],
            'car_version_2_id ' => [
                'label' => 'Car Version 2',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'v2.id',
                ],
                'value' => function ($model) {
                    return $model->version_2_name;
                },
            ],
            'action' => [
                'routePrefix' => 'admin.comparison',
                'contentCssClass' => 'grid-action-col',
            ]


        ];
    }
}
