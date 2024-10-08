<?php

namespace App\DataGrids\Admin;

use App\Models\News;
use Rufaidulk\DataGrid\Grid;


class NewsPostDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = News::query()
            ->where('posted_by', News::POSTED_BY_Q8CARS)
            // ->where('type', News::NOT_VIDEO_STORY)
            ->leftJoin('cars AS c', 'c.id', '=', 'car_id')
            ->leftJoin('brands AS b', 'b.id', '=', 'news.brand_id')
            ->orderByDesc('news.posted_time')
            ->select([
                'b.name as brand_name',
                'c.model_name as model_name',
                'news.status AS news_status',
                'news.is_trending as is_trending',
                'news.*'
            ]);

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
                    $url = file_asset('files-news', $model->image);
                    return "<img src='{$url}' alt='news-img' class='img-thumbnail' width='100' height='150'>";
                }
            ],
            // 'code' => [
            //     'label' => 'Code',
            //     'filter' => true,
            // ],
            'brand_id' => [
                'label' => 'Brand',
                'value' => function ($model) {
                    return $model->brand_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'b.name',
                ]
            ],
            'car_id' => [
                'label' => 'Car Model',
                'value' => function ($model) {
                    return $model->model_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'c.model_name',
                ]
            ],
            'media_name' => [
                'label' => 'Media Name',
                'filter' => true,
            ],
            'title' => [
                'label' => 'Title',
                'filter' => true,
            ],

            'posted_time' => [
                'label' => 'Published Date',
                'filter' => false,
                'value' => function ($model) {
                    return dateFormat($model->posted_time);
                }
            ],
            'expiry_date' => [
                'label' => 'Expiry Date',
                'filter' => false,
                'value' => function ($model) {
                    return dateFormat($model->expiry_date);
                }
            ],
            'is_trending' => [
                'label' => 'Is Trending News',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => config('params.news.is_trending'),
                    'attribute' => 'news.is_trending',

                ],
                'value' => function ($model) {
                    return config('params.news.is_trending')[$model->is_trending];
                }
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => config('params.news.status'),
                    'attribute' => 'news.status',

                ],
                'value' => function ($model) {
                    return config('params.news.status')[$model->news_status];
                }
            ],
                'action' => [
                    'routePrefix' => 'admin.news',
                    'contentCssClass' => 'grid-action-col',
                    'buttons' => ['view', 'update', 'newstoggle', 'delete'],
                    'newstoggle' => function ($model) {
                        if($model->show_in_detail_page !== News::SELECTED_BANNER){
                            
                            $btn = "<a onclick='toggleNews(this)' 
                        data-id='{$model->id}' 
                        data-car-id='{$model->car_id}' 
                        data-show_in_detail_page = '{$model->show_in_detail_page}'
                        data-is_trending = '{$model->is_trending}'
                        data-status = '{$model->status}'
                        class='btn btn-warning btn-icon waves-effect waves-light m-b-5 mr-1' 
                        title='update banner'>";
                            $btn .= "<span class='ion-flash'></span></a>";

                            return $btn;
                        }
                        
                    },

                ]
        ];
    }
}
