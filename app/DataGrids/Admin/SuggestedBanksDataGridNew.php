<?php

namespace App\DataGrids\Admin;

use App\Models\BankSuggestionRequest;
use Rufaidulk\DataGrid\Grid;

class SuggestedBanksDataGridNew extends Grid
{
    public function gridQuery()
    {
        $query = BankSuggestionRequest::query()
            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->select([
                'bank_suggestion_requests.id',
                'user_id',
                'first_name',
                'last_name',
                'civil_id',
                'bank_suggestion_requests.email',
                'bank_suggestion_requests.status',
                'bank_name',
                'users.mobile as user_mobile',
                'bank_suggestion_requests.contact_number',
            ])
            ->where('type', BankSuggestionRequest::TYPE_BANK)
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
            //     ]
            // ],

            // 'last_name' => [
            //     'label' => 'Last Name',
            //     'value' => function($model){
            //         return $model->last_name;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'attribute' => 'bank_suggestion_requests.last_name',
            //     ]
            // ],

            'bank_name' => [
                'label' => 'Bank Name',
                'value' => function ($model) {
                    return $model->bank_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.bank_name',
                ]
            ],

            'civil_id' => [
                'label' => 'Civil ID',
                'value' => function ($model) {
                    return $model->civil_id;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.civil_id',
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


            // 'action' => [
            //     'routePrefix' => 'admin.suggested-banks',
            //     'contentCssClass' => 'grid-action-col'
            // ],

            'action' => [
                'routePrefix' => 'admin.suggested-banks',
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
