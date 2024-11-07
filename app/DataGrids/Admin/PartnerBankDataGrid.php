<?php

namespace App\DataGrids\Admin;

use App\Models\Bank;
use Rufaidulk\DataGrid\Grid;

class PartnerBankDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = Bank::query()->select(['id', 'bank_name', 'branch_name', 'city', 'logo', 'status'])
        ->orderBy('banks.id', 'Desc');
        return $query;
    }

    public function columns()
    {
        return [
            'logo' => [
                'label' => 'Logo',
                'filter' => false,
                'sort' => false,
                'value' => function ($model) {
                    if ($model->logo) {
                        $url = file_asset('files-banks', $model->logo);
                        return "<img src='{$url}' alt='logo' class='img-thumbnail img-list'>";
                    }
                }
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

            // 'branch_name' => [
            //     'label' => 'Branch Name',
            //     'value' => function($model){
            //         return $model->branch_name;
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'attribute' => 'banks.branch_name',
            //     ]
            // ],

            'city' => [
                'label' => 'City',
                'value' => function($model){
                    return $model->city;
                },
                'filter' => true,
                'filterOptions' => [
                    'attribute' => 'banks.city',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'data' => [
                        Bank::STATUS_ACTIVE => 'Active',
                        Bank::STATUS_INACTIVE => 'Inactive',
                    ],
                    'attribute' => 'banks.status',
                ],
                'value' => function ($model) {
                    return [
                        Bank::STATUS_ACTIVE => 'Active',
                        Bank::STATUS_INACTIVE => 'Inactive',
                    ][$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.partner-banks',
                'contentCssClass' => 'grid-action-col'
            ],
        ];
    }
}
