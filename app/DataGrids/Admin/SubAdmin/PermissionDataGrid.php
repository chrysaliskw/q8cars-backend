<?php

namespace App\DataGrids\Admin\SubAdmin;

use Rufaidulk\DataGrid\Grid;
use Spatie\Permission\Models\Permission;

use function Laravel\Prompts\select;

class PermissionDataGrid extends Grid
{
    public function gridQuery()
    {
        $query = Permission::query()->orderBy('id', 'Desc')->select('permissions.*')->where('name', '!=', 'All');
        // dd($query->get());
    return $query;
    }

    public function columns()
    {
        return [
            'name' => [
                'label' => 'Name',
                'value' => function ($model) {
                    return $model->name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'name',
                    ]
                ],
            'created_at' => [
                'label' => 'Created At',
                'value' => function ($model) {
                    return $model->created_at;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'created_at',
                    ]
            ],
        ];
    }
}
