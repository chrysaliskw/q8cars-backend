<?php

namespace App\DataGrids\Admin;

use App\Models\Review;
use App\Models\TestDrive;
use Rufaidulk\DataGrid\Grid;
use App\Services\FaRatingHtmlService;

class ReviewDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Review::query()
            ->leftJoin('users as u', 'u.id', '=', 'reviews.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'reviews.car_id')
            ->leftJoin('brands', 'brands.id', '=', 'c.brand_id')
            ->select(['reviews.*', 'u.phone_code as user_phone_code', 'u.mobile as user_mobile', 'c.model_name as car_model'])
            ->orderBy('reviews.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return [

            'user_mobile' => [
                'label' => 'User Mobile',
                'value' => function ($model) {
                    return "<a href='" . route('admin.user.show', $model->user->id) . "'>  $model->user_mobile</a>";
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'u.mobile',
                ]
            ],
            'car_model' => [
                'label' => 'Car Model',
                'value' => function ($model) {
                    return $model->car_model;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'c.model_name',
                ]
            ],

            'short_comment' => [
                'label' => 'Title',
                'value' => function ($model) {
                    return $model->short_comment;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'short_comment',
                ]
            ],
            'rating' => [
                'label' => 'Rating',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => [
                        1 => 1,
                        2 => 2,
                        3 => 3,
                        4 => 4,
                        5 => 5
                    ]
                ],
                'value' => function ($model) {
                    return (new FaRatingHtmlService($model->rating))->handle();
                },
                'contentCssClass' => 'review-rating-cell',
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'reviews.status',
                    'operator' => '=',
                    'data' => config('params.review.status')
                ],
                'value' => function ($model) {
                    return config('params.review.status')[$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.reviews',
                'buttons' => ['view', 'update'],
                'update' => function ($model) {
                    if ($model->status == Review::STATUS_SUBMITTED) {
                        $btn = "<a onclick='openUpdateStatusModal(this)' data-id='{$model->id}' data-status='{$model->status}'class='btn btn-info btn-icon waves-effect waves-light m-b-5 mr-1' title='Update'>";
                        $btn .= "<span class='ion-edit'></span></a>";
                        return $btn;
                    }
                }

            ]
        ];
    }
}
