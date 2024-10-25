<?php

namespace App\DataGrids\Admin;

use App\Models\Notification;
use Rufaidulk\DataGrid\Grid;

class NotificationDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = Notification::query()->select(['id', 'image', 'title', 'start_date', 'end_date', 'status'])
        ->orderBy('notifications.id', 'Desc');
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
                    if ($model->image) {
                        $url = file_asset('notifications', $model->image);
                        return "<img src='{$url}' alt='notification-img' class='img-thumbnail img-list'>";
                    }
                }
            ],

            'title' => [
                'label' => 'Title',
                'value' => function($model){
                    return $model->title;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'notifications.title',
                ]
            ],

            'start_date' => [
                'label' => 'Start Date',
                'value' => function ($model) {
                    return dateFormat($model->start_date);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'notifications.start_date',
                ]
            ],

            'end_date' => [
                'label' => 'End Date',
                'value' => function ($model) {
                    return dateFormat($model->end_date);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'notifications.end_date',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => [
                        Notification::STATUS_ACTIVE => 'Active',
                        Notification::STATUS_INACTIVE => 'Inactive',
                        Notification::STATUS_EXPIRED => 'Expired',
                    ],
                    'attribute' => 'notifications.status',
                ],
                'value' => function ($model) {
                    return [
                        Notification::STATUS_ACTIVE => 'Active',
                        Notification::STATUS_INACTIVE => 'Inactive',
                        Notification::STATUS_EXPIRED => 'Expired',
                    ][$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.notifications',
                'contentCssClass' => 'grid-action-col'
            ],
        ];
    }
}

