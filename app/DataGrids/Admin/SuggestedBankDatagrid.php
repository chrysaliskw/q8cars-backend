<?php

namespace App\DataGrids\Admin;

use App\Models\BankSuggestionRequest;
use Rufaidulk\DataGrid\Grid;

class SuggestedBankDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = BankSuggestionRequest::query()->select(['id', 'user_id', 'first_name', 'last_name', 'civil_id', 'email', 'status', 'bank_name'])
        ->orderBy('bank_suggestion_requests.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return[
            'bank_name' => [
                'label' => 'Bank Name',
                'value' => function($model){
                    return $model->bank_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.bank_name',
                ]
            ],

            'first_name' => [
                'label' => 'First Name',
                'value' => function($model){
                    return $model->first_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.first_name',
                ]
            ],

            'last_name' => [
                'label' => 'Last Name',
                'value' => function($model){
                    return $model->last_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.last_name',
                ]
            ],

            'civil_id' => [
                'label' => 'Civil ID',
                'value' => function($model){
                    return $model->civil_id;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'bank_suggestion_requests.civil_id',
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
                    'data' => [
                        BankSuggestionRequest::STATUS_SUBMITTED => 'Submitted',
                        BankSuggestionRequest::STATUS_ACCEPTED => 'Accepted',
                        BankSuggestionRequest::STATUS_REJECTED => 'Rejected',
                    ],
                    'attribute' => 'bank_suggestion_requests.status',
                ],
                'value' => function ($model) {
                    return [
                        BankSuggestionRequest::STATUS_SUBMITTED => 'Submitted',
                        BankSuggestionRequest::STATUS_ACCEPTED => 'Accepted',
                        BankSuggestionRequest::STATUS_REJECTED => 'Rejected',
                    ][$model->status];
                },
            ],


            // 'action' => [
            //     'routePrefix' => 'admin.suggested-banks',
            //     'contentCssClass' => 'grid-action-col'
            // ],

            'action' => [
                'routePrefix' => 'admin.suggested-banks',
                'buttons' => ['view','update'],
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
