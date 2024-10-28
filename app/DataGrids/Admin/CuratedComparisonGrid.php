<?php

namespace App\DataGrids\Admin;

use App\Models\CuratedComparison;
use Rufaidulk\DataGrid\Grid;

class CuratedComparisonGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = CuratedComparison::query()
            ->leftJoin('cars as c', 'c.id', 'curated_comparisons.car_id_1')
            ->leftJoin('cars as c1', 'c1.id', 'curated_comparisons.car_id_2')
            ->leftJoin('cars as c2', 'c2.id', 'curated_comparisons.car_id_3')

            ->leftJoin('brands as b', 'b.id', 'curated_comparisons.brand_id_1')
            ->leftJoin('brands as b1', 'b1.id', 'curated_comparisons.brand_id_2')
            ->leftJoin('brands as b2', 'b2.id', 'curated_comparisons.brand_id_3')

            ->select([
                'c.id as car_model_id',
                'c.model_name as car_model_name',

                'c1.id as car_1_model_id',
                'c1.model_name as car_1_model_name',

                'c2.id as car_2_model_id',
                'c2.model_name as car_2_model_name',


                'b.id as brand_id',
                'b.name as brand_name',

                'b1.id as brand_1_id',
                'b1.name as brand_1_name',

                'b2.id as brand_2_id',
                'b2.name as brand_2_name',
                // 'curated_comparisons.page as display_page',

                'curated_comparisons.*'
            ])
            ->orderByDesc('curated_comparisons.id');


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
                    if ($model->image_1) {
                        $url = file_asset('files-curated-comparisons', $model->image_1);
                        return "<img src='{$url}' alt='car-img' class='img-thumbnail img-list'>";
                    }
                }
            ],

         'title' => [
                'label' => 'Title',
                'value' => function ($model) {
                    return $model->title;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'title',
                ]
            ],

         'source' => [
                'label' => 'Source',
                'value' => function ($model) {
                    return $model->source;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'source',
                ]
            ],

          'published_date' => [
                'label' => 'Published Date',
                'value' => function ($model) {
                    return $model->published_date;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'published_date',
                ]
            ],
                
           'status' => [
    'label' => 'Status',
    'filter' => true,
    'filterOptions' => [
        'type' => 'select',
        'data' => config('params.curated-comparisons.status'),
        'attribute' => 'curated_comparisons.status', // Corrected table name
    ],
    'value' => function ($model) {
        return config('params.curated-comparisons.status')[$model->status];
    },
],

           

            
            'action' => [
                'routePrefix' => 'admin.curated-comparison',
                'contentCssClass' => 'grid-action-col',
            ]


        ];
    }
}
