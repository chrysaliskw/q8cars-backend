<?php

namespace App\DataGrids\Admin\Reports;

use App\Models\User;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserReportDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {

        $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');
        $query = User::query()
            ->where('id', '!=', 0)->orderBy('id', 'Desc')
            ->select(['users.*', DB::raw("CONCAT(users.phone_code, users.mobile) as full_mobile")]);
        $query->when(request()->query('name'), function ($q, $name) {
            $q->where('name', 'like', '%' . $name . '%');
        });

        $query->when(request()->query('email'), function ($q, $email) {
            $q->where('email', 'like', '%' . $email . '%');
        });

        $query->when(request()->query('mobile'), function ($q, $mobile) {
            $q->where(DB::raw("CONCAT(users.phone_code, users.mobile)"), 'like', '%' . $mobile . '%');
        });

        $query->when(request()->query('start_date'), function ($q) use ($startDate, $endDate) {
            $q->where('users.created_at', '>=', $startDate)
                ->where('users.created_at', '<=', $endDate);
        });

        return $query;
    }

    public function columns()
    {
        return [

            'mobile' => [
                'label' => 'User Mobile',
                'value' => function ($model) {
                    return $model->phone_code . $model->mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'mobile',
                ]
            ],
            'name' => [
                'label' => 'User Name',
                'value' => function ($model) {
                    return $model->name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'name',
                ]
            ],
            'email' => [
                'label' => 'User Email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'email',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'users.status',
                    'operator' => '=',
                    'data' => config('params.user.status')
                ],
                'value' => function ($model) {
                    return config('params.user.status')[$model->status];
                },
                'contentCssClass' => 'filter',
            ],
            'created_at' => [
                'label' => 'Created Date',
                'value' => function ($model) {
                    return dateFormat($model->created_at);
                },
                'filter' => false,
            ],

        ];
    }
}
