<?php

namespace App\DataGrids\Admin;

use App\Models\Faq;
use Rufaidulk\DataGrid\Grid;

class FaqDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Faq::query()
            ->leftJoin('brands as b', 'b.id', 'faqs.brand_id')
            ->leftJoin('cars as c', 'c.id', 'faqs.car_id')
            ->leftJoin('car_versions as v', 'v.id', 'faqs.car_version_id')
            ->select([
                'b.id as brand_id',
                'b.name as brand_name',
                'c.id as car_model_id',
                'c.model_name as car_model_name',
                'v.id as version_id',
                'v.varient_name as version_name',
                'faqs.*'
            ])
            ->orderByDesc('faqs.id');

        return $query;
    }

    public function columns()
    {
        return [
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
            'car_id' => [
                'label' => 'Car',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c.model_name',
                ],
                'value' => function ($model) {
                    return $model->car_model_name;
                },
            ],
            'question' => [
                'label' => 'Question',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'question',
                ],
                'value' => function ($model) {
                    return $model->question;
                },
            ],
            'sort_order' => [
                'label' => 'Sort Order',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'faqs.sort_order',
                ],
                'value' => function ($model) {
                    return $model->sort_order;
                },
            ],
            'question_status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'question_status',
                    'operator' => '=',
                    'data' => config('params.faq.status')
                ],
                'value' => function ($model) {
                    return config('params.faq.status')[$model->question_status];
                }
            ],
            'action' => [
                'routePrefix' => 'admin.faq',
                'contentCssClass' => 'grid-action-col'
            ]
        ];
    }
}
