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
    $endDate = Carbon::parse(request()->query('end_date'))
        ->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
    $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

    $query = BankSuggestionRequest::query()
        ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
        ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
        ->when(request()->query('bank_name'), function ($q) {
            $q->where('banks.bank_name', request()->query('bank_name'));
        })
        ->when(request()->query('name'), function ($q) {
            $q->where('users.name', request()->query('name'));
        })
       
        ->when(request()->query('status'), function ($q) {
            $q->where('bank_suggestion_requests.status', request()->query('status'));
        })
        ->select(['bank_suggestion_requests.id', 'first_name',
         'last_name', 'contact_number', 'bank_suggestion_requests.email', 'bank_suggestion_requests.status', 'banks.bank_name as bank_name', 'users.name as requested_user'])
        ->where('type', BankSuggestionRequest::TYPE_LOAN)
        ->orderBy('bank_suggestion_requests.id', 'Desc');
        return $query;
}


            public function columns()
            {
                return[
        
                    'requested_user' => [
                        'label' => 'Requested User',
                        'value' => function($model){
                            return $model->requested_user;
                        },
                        'filter' => true,
                        'filterOptions' => [
                            'attribute' => 'users.name',
                        ]
                    ],
        
                    'first_name' => [
                        'label' => 'Full Name',
                        'value' => function($model){
                            return trim($model->first_name . ' ' . $model->last_name);
                        },
                        'filter' => true,
                        'filterOptions' => [
                            'attribute' => 'bank_suggestion_requests.first_name',
                            ]
                        ],
        
                    'bank_name' => [
                        'label' => 'Bank Name',
                        'value' => function($model){
                            return $model->bank_name;
                        },
                        'filter' => true,
                        'filterOptions' => [
                            'attribute' => 'banks.bank_name',
                        ]
                    ],
        
                    'contact_number' => [
                        'label' => 'Contact Number',
                        'value' => function($model){
                            return $model->contact_number;
                        },
                        'filter' => true,
                        'filterOptions' => [
                            'attribute' => 'bank_suggestion_requests.contact_number',
                        ]
                    ],
        
                    'email' => [
                        'label' => 'Email',
                        'value' => function($model){
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
