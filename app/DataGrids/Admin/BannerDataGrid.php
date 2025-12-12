<?php

namespace App\DataGrids\Admin;

use App\Models\Banner;
use App\Models\User;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;

class BannerDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Banner::query()
            ->where('id', '!=', 0)->orderBy('id', 'Desc')
            ->select(['banners.*']);

        return $query;
    }

    public function columns()
    {
        return [
            'file_name' => [
                'label' => 'Image',
                'filter' => false,
                'sort' => false,
                'value' => function ($model) {
                    if ($model->file_name) {
                        $url = file_asset('files-banner', $model->file_name);
                        return "<img src='{$url}' alt='body-type-img' class='img-thumbnail img-list-user'>";
                    } else {
                        return '<img src="' . asset('moltran-asset/images/dp.png') . '" alt="profile-img" class="img-thumbnail img-list-user">';
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
            'page' => [
                'label' => 'Page',
                'value' => function ($model) {
                    return $model->page;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'banners.page',
                    'operator' => '=',
                    'data' => config('params.banner.page')
                ],
                'value' => function ($model) {
                    return config('params.banner.page')[$model->page];
                },
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

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'banners.status',
                    'operator' => '=',
                    'data' => config('params.banner.status')
                ],
                'value' => function ($model) {
                    return config('params.banner.status')[$model->status];
                },
                'contentCssClass' => 'filter',
            ],

            'action' => [
                'routePrefix' => 'admin.banner',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
