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
                    if ($model->car->image) {
                        $url = file_asset( 'files-car', $model->car->image);
                        return "<img src='{$url}' alt='car-img' class='img-thumbnail img-list'>";
                    }
                }
            ],

            'brand' => [
                'label' => 'Brand',
                'value' => function($model){
                    return $model->brand_name;
                },
                'filter' => true
            ],

            'car_model' => [
                'label' => 'Car Model',
                'value' => function($model){
                    return $model->car_model;
                },
                'filter' => true
            ],

            'car_variant' => [
                'label' => 'Car Variant',
                'value' => function ($model) {
                    return $model->car_varient;
                },
                'filter' => true,
            ],

            'title' => [
                'label' => 'Title',
                'value' => function($model){
                    return $model->title;
                },
                'filter' => true
            ],

            'offer' => [
                'label' => 'Offer',
                'value' => function ($model) {
                    return $model->offer;
                },
                'filter' => true,
            ],

            'start_date' => [
                'label' => 'Start Date',
                'value' => function ($model) {
                    return $model->start_date;
                },
                'filter' => true,
            ],

            'end_date' => [
                'label' => 'End Date',
                'value' => function ($model) {
                    return $model->end_date;
                },
                'filter' => true,
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
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
