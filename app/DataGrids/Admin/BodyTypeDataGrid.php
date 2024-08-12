<?php

namespace App\DataGrids\Admin;

use App\Models\BodyType;
use Rufaidulk\DataGrid\Grid;

class BodyTypeDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = BodyType::query() 
            ->select(['body_types.*']);

        return $query;
    }

    public function columns()
    {
        return [
            'icon' => [
                'label' => 'Image',
                'filter' => false,
                'sort' => false,
                'value' => function ($model) {
                    if ($model->icon) {
                        $url = file_asset('files-body_type', $model->icon);
                        return "<img src='{$url}' alt='body-type-img' class='img-thumbnail' width='100' height='150'>";
                    } else {
                        return '<img src="' . asset('moltran-asset/images/dp.png') . '" alt="profile-img" class="img-thumbnail rounded-circle" width="100" height="150">';
                    }
                }
            ],
            'name' => [
                'label' => 'Name',
                'value' => function ($model) {
                    return $model->name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'name',
                ]
            ],


            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'brands.status',
                    'operator' => '=',
                    'data' => config('params.brand.status')
                ],
                'value' => function ($model) {
                    return config('params.brand.status')[$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.body-type', 
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
