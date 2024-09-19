<?php

namespace App\DataGrids\Admin;

use App\Models\BrandColorMapping;
use Rufaidulk\DataGrid\Grid;

class ColorDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = BrandColorMapping::query()->
        leftjoin('brands','brand_color_mappings.brand_id','brands.id')->orderBy('id','Desc')
            ->select(['brand_color_mappings.*','brands.name as brand_name','brands.id as brandId']);

        return $query;
    }

    public function columns()
    {
        return [
            'name' => [
                'label' => 'Name',
                'value' => function ($model) {
                    return $model->name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'brand_color_mappings.name',
                ]
            ],
            'brand_id' => [
                'label' => 'Brand',
                'value' => function ($model) {
                    return $model->brand->name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'brands.name',
                ]
            ],



            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'brand_color_mappings.status',
                    'operator' => '=',
                    'data' => config('params.brand_color.status')
                ],
                'value' => function ($model) {
                    return config('params.brand_color.status')[$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.color',
               
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
