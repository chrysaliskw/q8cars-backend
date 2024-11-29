<?php

namespace App\DataGrids\Admin\SubAdmin;

use Spatie\Permission\Models\Role;
use Rufaidulk\DataGrid\Grid;

class RoleDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Role::query()->orderBy('id', 'Desc')
            ->select(['roles.*']);

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
                    'attribute' => 'roles.name',
                ]
            ],
            'Created At' => [
                'label' => 'Created At',
                'value' => function ($model) {
                    return $model->created_at;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'roles.created_at',
                ]
            ],




            'action' => [
                'routePrefix' => 'admin.sub-admin.role',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
