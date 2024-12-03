<?php

namespace App\DataGrids\Admin;

use App\Models\BankSuggestionRequest;
use Rufaidulk\DataGrid\Grid;

class LoanRequestDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = BankSuggestionRequest::query()
            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
            ->select(['bank_suggestion_requests.id', 'user_id', 'first_name', 'last_name', 'contact_number', 'bank_suggestion_requests.email', 'bank_suggestion_requests.status', 'banks.bank_name as bank_name', 'users.mobile as user_mobile'])
            ->where('type', BankSuggestionRequest::TYPE_LOAN)
            // ->when(request()->query('first_name'), function($query){
        //     $query->where('bank_suggestion_requests.first_name', 'like', '%'.request()->query('first_name').'%')
        //     ->orWhere('bank_suggestion_requests.last_name', 'like', '%'.request()->query('last_name').'%');
        // })
        ->orderBy('bank_suggestion_requests.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return [

            'user_mobile' => [
                'label' => 'User Mobile',
                'value' => function ($model) {
                    // return trim($model->user->phone_code . ' ' . $model->user->mobile);
                    return "<a href='" . route('admin.user.show', $model->user_id) . "'> {$model->user->phone_code} {$model->user_mobile}</a>";
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'users.mobile',
                ]
            ],

            // 'requested_user' => [
            //     'label' => 'Requested User',
            //     'value' => function($model){
            //         return $model->requested_user;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'attribute' => 'users.name',
            //     ]
            // ],

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
                'label' => 'Requested Mobile',
                'value' => function($model){
                    return '+965 '. $model->contact_number;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.contact_number',
                ]
            ],

            'email' => [
                'label' => 'Requested Email',
                'value' => function($model){
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
                'buttons' => ['view', 'update'],
                'update' => function ($model) {
                    if ($model->status != BankSuggestionRequest::STATUS_ACCEPTED) {
                        return "<a onclick='openUpdateStatusModal(this)' data-id='{$model->id}' data-status='{$model->status}' class='btn btn-info btn-icon waves-effect waves-light m-b-5 mr-1' title='Update'>
                                    <span class='ion-edit'></span>
                                </a>";
                    }
                },
            ]
        ];
    }
}
