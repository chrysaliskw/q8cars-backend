<?php

namespace App\DataGrids\Admin;

use App\Models\Brand;
use Rufaidulk\DataGrid\Grid;

class BrandDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Brand::query()->orderBy('id', 'Desc')
            ->select(['brands.*']);

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
                        $url = file_asset('files-brand', $model->icon);
                        return "<img src='{$url}' alt='brand-img' class='img-thumbnail img-list'>";
                    } else {
                        return '<img src="' . asset('moltran-asset/images/dp.png') . '" alt="profile-img" class="img-thumbnail img-list rounded-circle">';
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
            'is_top_brand' => [
                'label' => 'Is Top Brand',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'brands.is_top_brand',
                    'operator' => '=',
                    'data' => config('params.brand.is_top_brand')
                ],
                'value' => function ($model) {
                    return config('params.brand.is_top_brand')[$model->is_top_brand];
                },
            ],
            'is_recently_purchased' => [
                'label' => 'Is Recently Purchased',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'brands.is_recently_purchased',
                    'operator' => '=',
                    'data' => config('params.brand.is_recently_purchased')
                ],
                'value' => function ($model) {
                    return config('params.brand.is_recently_purchased')[$model->is_recently_purchased];
                },
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
                'routePrefix' => 'admin.brand',

                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
