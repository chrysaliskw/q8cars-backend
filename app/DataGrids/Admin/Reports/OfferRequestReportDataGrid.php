<?php

namespace App\DataGrids\Admin\Reports;

use App\Models\OfferRequest;
use App\Models\TestDrive;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Carbon;

class OfferRequestReportDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

        $query = OfferRequest::query()
            ->leftJoin('users as u', 'u.id', '=', 'offer_requests.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'offer_requests.car_id')
            // ->leftJoin('offers', 'offers.id', '=', 'offer_requests.offer_id')
            ->select(['offer_requests.*', 'u.phone_code as user_phone_code', 'u.mobile as user_mobile', 'c.model_name as car_model'])
            ->orderBy('offer_requests.id', 'Desc');

        $query->when(request()->query('car_1_id'), function ($query, $model) {
            $query->where('offer_requests.car_id', request()->query('car_1_id'));
        });
        $query->when(request()->query('mobile'), function ($query, $model) {
            $query->where('u.mobile', 'like', '%' . $model . '%');
        });
        $query->when(request()->query('type'), function ($query, $model) {
            $query->where('offer_requests.type',  $model);
        });
        $query->when(request()->query('status'), function ($query, $model) {
            $query->where('offer_requests.status',  $model);
        });
        $query->when(request()->query('start_date'), function ($query) use ($startDate, $endDate) {
            $query->where('offer_requests.created_at', '>=', $startDate)
                ->where('offer_requests.created_at', '<=', $endDate);
        });
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

            'full_name' => [
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
                    'attribute' => 'offer_requests.mobile',
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
                    'attribute' => 'offer_requests.email',
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
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'offer_requests.status',
                    'operator' => '=',
                    'data' => config('params.offer_request.status')
                ],
                'value' => function ($model) {
                    return config('params.offer_request.status')[$model->status];
                },
            ],

        ];
    }
}
