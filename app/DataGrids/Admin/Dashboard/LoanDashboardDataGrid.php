<?php

namespace App\DataGrids\Admin\Dashboard;

use App\Models\BankSuggestionRequest;
use Illuminate\Support\Facades\Auth;
use Rufaidulk\DataGrid\Grid;

class LoanDashboardDataGrid extends Grid
{
    public $pageSize = 5;

    public function renderPaginationLinks()
    {
        return null;
    }

    public function gridQuery()
    {
        $query = BankSuggestionRequest::query()
            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
            ->select(['bank_suggestion_requests.id', 'first_name', 'last_name', 'contact_number', 'bank_suggestion_requests.email', 'bank_suggestion_requests.status', 'banks.bank_name as bank_name', 'users.name as requested_user'])
            ->where('type', BankSuggestionRequest::TYPE_LOAN)
            ->orderBy('bank_suggestion_requests.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return [

            'requested_user' => [
                'label' => 'Requested User',
                'value' => function ($model) {
                    return $model->requested_user;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'users.name',
                ]
            ],

            'first_name' => [
                'label' => 'Full Name',
                'value' => function ($model) {
                    return trim($model->first_name . ' ' . $model->last_name);
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.first_name',
                ]
            ],

            // 'first_name' => [
            //     'label' => 'First Name',
            //     'value' => function($model){
            //         return $model->first_name;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'attribute' => 'bank_suggestion_requests.first_name',
            //         ]
            //     ],

            // 'last_name' => [
            //     'label' => 'Last Name',
            //     'value' => function($model){
            //         return $model->last_name;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'attribute' => 'bank_suggestion_requests.last_name',
            //         ]
            //     ],

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
                'label' => 'Contact Number',
                'value' => function ($model) {
                    return $model->contact_number;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.contact_number',
                ]
            ],

            'email' => [
                'label' => 'Email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.email',
                ]
            ],

            // 'status' => [
            //     'label' => 'Status',
            //     'filter' => true,
            //     'filterOptions' => [
            //         'type' => 'select',
            //         'data' => [
            //             BankSuggestionRequest::STATUS_SUBMITTED => 'Submitted',
            //             BankSuggestionRequest::STATUS_ACCEPTED => 'Accepted',
            //             BankSuggestionRequest::STATUS_REJECTED => 'Rejected',
            //         ],
            //         'attribute' => 'bank_suggestion_requests.status',
            //     ],
            //     'value' => function ($model) {
            //         return [
            //             BankSuggestionRequest::STATUS_SUBMITTED => 'Submitted',
            //             BankSuggestionRequest::STATUS_ACCEPTED => 'Accepted',
            //             BankSuggestionRequest::STATUS_REJECTED => 'Rejected',
            //         ][$model->status];
            //     },
            // ],

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

            'action' => [
                'routePrefix' => 'admin.loan-requests',
                'buttons' => ['view'],
                // 'view' => function ($model) {
                //     if (Auth::user()->can('All') || Auth::user()->can('Test Ride Request')) {
                //         return "<a href='" . route('admin.loan-requests.show', $model->id) . "' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#updateStatusModal'>View</a>";
                //     }
                // },
            ]
        ];
    }
}
