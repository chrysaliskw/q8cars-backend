<?php

namespace App\DataGrids\Admin\Reports;

use App\Models\BankSuggestionRequest;
use App\Models\TestDrive;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanRequestReportDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';


    public function gridQuery()
    {
        $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

        $query = BankSuggestionRequest::query()

            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
            ->select([
                'bank_suggestion_requests.*',
                'bank_suggestion_requests.id',
                'first_name',
                'last_name',
                'contact_number',
                'bank_suggestion_requests.email',
                'bank_suggestion_requests.status',
                'banks.bank_name as bank_name',
                'users.name as requested_user',
                'users.mobile as requested_mobile',
            ])
            ->where('type', BankSuggestionRequest::TYPE_LOAN)
            ->orderBy('bank_suggestion_requests.id', 'Desc');
        // logger($query);
        $query->when(request('bank_id'), function ($query, $model) {
            $query->where('bank_id', $model);
        });

        $query->when(request('name'), function ($query, $model) {
            $query->where('name', 'like', '%' . $model . '%');
        });

        $query->when(request('status'), function ($query, $model) {
            $query->where('bank_suggestion_requests.status', $model);
        });
        $query->when(request('start_date'), function ($query, $model) use ($startDate, $endDate) {
            $query->where('bank_suggestion_requests.created_at', '>=', $startDate)
                ->where('bank_suggestion_requests.created_at', '<=', $endDate);
        });

        return $query;
    }


    public function columns()
    {
        return [

            'requested_user' => [
                'label' => ' User Name',
                'value' => function ($model) {
                    return $model->requested_user;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'users.name',
                ]
            ],
            'requested_mobile' => [
                'label' => ' User Mobile',
                'value' => function ($model) {
                    return $model->requested_mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'users.mobile',
                ]
            ],

            'first_name' => [
                'label' => 'Requested Name',
                'value' => function ($model) {
                    return trim($model->first_name . ' ' . $model->last_name);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.first_name',
                ]
            ],

            'bank_name' => [
                'label' => 'Bank Name',
                'value' => function ($model) {
                    return $model->bank_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'banks.bank_name',
                ]
            ],

            'contact_number' => [
                'label' => 'Requested Mobile',
                'value' => function ($model) {
                    return $model->contact_number;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.contact_number',
                ]
            ],

            'email' => [
                'label' => 'Requested Email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.email',
                ]
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => config('params.banks.status'),
                    'attribute' => 'bank_suggestion_requests.status',
                ],
                'value' => function ($model) {
                    return config('params.banks.status')[$model->status] ?? 'Unknown';
                },
            ],
            // 'created_at' => [
            //     'label' => 'Created Date',
            //     'value' => function ($model) {
            //         // dd($model->created_at);
            //         return dateFormat($model->created_at);
            //     },
            //     'filter' => false,
            // ],
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
