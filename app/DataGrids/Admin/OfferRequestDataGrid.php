<?php

namespace App\DataGrids\Admin;

use App\Models\OfferRequest;
use App\Models\TestDrive;
use Rufaidulk\DataGrid\Grid;

class OfferRequestDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = OfferRequest::query()
            ->leftJoin('users as u', 'u.id', '=', 'offer_requests.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'offer_requests.car_id')
           // ->leftJoin('offers', 'offers.id', '=', 'offer_requests.offer_id')
            ->select(['offer_requests.*','u.phone_code as user_phone_code','u.mobile as user_mobile','c.model_name as car_model'])
            ->orderBy('offer_requests.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return [
           
            'user_mobile' => [
                'label' => 'User Mobile',
                'value' => function ($model) {
                    return "<a href='" . route('admin.user.show', $model->user->id) . "'> $model->user_phone_code $model->user_mobile</a>";
                  
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'user_mobile',
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
                    'attribute' => 'car_model',
                ]
            ],
            
            'first_name' => [
                'label' => 'Requested Name',
                'value' => function ($model) {
                    return $model->full_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'full_name',
                ]
            ],
            'mobile' => [
                'label' => 'Requested Mobile',
                'value' => function ($model) {
                    return $model->phone_code . $model->mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'mobile',
                ]
            ],
            'email' => [
                'label' => 'Requested Email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'email',
                ]
            ],
            'type' => [
                'label' => 'Type',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'offer_requests.type',
                    'operator' => '=',
                    'data' => config('params.offer_request.type')
                ],
                'value' => function ($model) {
                    return config('params.offer_request.type')[$model->type];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.offer-requests',
                'buttons' => ['view'],
            ]
        ];
    }
}
