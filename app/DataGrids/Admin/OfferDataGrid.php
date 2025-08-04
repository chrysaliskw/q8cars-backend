<?php

namespace App\DataGrids\Admin;

use App\Models\Offer;
use Rufaidulk\DataGrid\Grid;

class OfferDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = Offer::query()
        ->leftJoin('cars as c', 'c.id', '=', 'offers.car_id')
        ->leftJoin('car_versions as cv', 'cv.id', '=', 'offers.car_version_id')
        ->leftJoin('brands as b', 'b.id', '=', 'offers.brand_id')
        ->select(['offers.*', 'c.model_name as car_model', 'cv.varient_name as car_varient', 'b.name as brand_name'])
        ->orderBy('offers.id', 'Desc');
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
                    if (!empty($model->car) && !empty($model->car->image)) {
                        $url = file_asset('files-car', $model->car->image);
                        return "<img src='{$url}' alt='car-img' class='img-thumbnail img-list'>";
                    }
                    return null;
                }
            ],

            'brand_id' => [
                'label' => 'Brand',
                'value' => function($model){
                    return $model->brand_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'b.name',
                ]
            ],

            'car_model' => [
                'label' => 'Car Model',
                'value' => function($model){
                    return $model->car_model;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'c.model_name',
                ]
            ],

            'car_varient' => [
                'label' => 'Car Variant',
                'value' => function ($model) {
                    return $model->car_varient;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'cv.varient_name',
                ]
            ],

            'title' => [
                'label' => 'Title',
                'value' => function($model){
                    return $model->title;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'offers.title',
                ]
            ],

            'offer' => [
                'label' => 'Offer',
                'value' => function ($model) {
                    return $model->offer;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'offers.offer',
                ]
            ],

            'start_date' => [
                'label' => 'Start Date',
                'value' => function ($model) {
                    return dateFormat($model->start_date);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'offers.start_date',
                ]
            ],

            'end_date' => [
                'label' => 'End Date',
                'value' => function ($model) {
                    return dateFormat($model->end_date);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'offers.end_date',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => config('params.offers.status'),
                    'attribute' => 'offers.status',
                ],
                'value' => function ($model) {
                    return config('params.offers.status')[$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.offers',
                'contentCssClass' => 'grid-action-col'
            ],
        ];
    }
}
