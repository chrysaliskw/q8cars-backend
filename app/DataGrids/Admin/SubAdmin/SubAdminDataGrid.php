<?php

namespace App\DataGrids\Admin\SubAdmin;

use App\Models\Admin;
use Google\Service\Dfareporting\Ad;
use Spatie\Permission\Models\Role;
use Rufaidulk\DataGrid\Grid;

class SubAdminDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = Admin::query()->orderBy('id', 'Desc')
            ->select(['admins.*'])
            ->where('admins.id', '!=', 1);

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
                    'attribute' => 'admins.name',
                ]
            ],
            'email' => [
                'label' => 'email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'admins.email',
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
                    'attribute' => 'admins.created_at',
                ]
            ],




            'action' => [
                'routePrefix' => 'admin.sub-admin.admin',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
