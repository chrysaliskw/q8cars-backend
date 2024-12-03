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
            ->leftJoin('roles as r', 'r.id', '=', 'admins.role')
            ->select(['admins.*', 'r.name as role_name'])
            ->where('admins.id', '!=', 1);

        // dd($query->get());
        return $query;
    }

    public function columns()
    {
        return [

            'picture' => [
                'label' => 'Image',
                'filter' => false,
                'sort' => false,
                'value' => function ($model) {
                    if ($model->picture) {
                        $url = file_asset('files-admin', $model->picture);
                        return "<img src='{$url}' alt='admin-img' class='img-thumbnail img-list'>";
                    } else {
                        return '<img src="' . asset('moltran-asset/images/dp.png') . '" alt="profile-img" class="img-thumbnail img-list">';
                    }
                }
            ],
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
            'role_name' => [
                'label' => 'Role',
                'value' => function ($model) {
                    return $model->role_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'r.name',
                ]
            ],
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'admins.status',
                    'operator' => '=',
                    'data' => config('params.admin.status')
                ],
                'value' => function ($model) {
                    return config('params.admin.status')[$model->status] ?? 'Unknown';
                },
            ],

            // 'created_at' => [
            //     'label' => 'Created Date',
            //     'value' => function ($model) {
            //         return dateFormat($model->created_at);
            //     },
            //     'filter' => true,
            //     'filterOptions' => [
            //         'type' => 'text',
            //         'attribute' => 'admins.created_at',
            //     ]
            // ],




            'action' => [
                'routePrefix' => 'admin.sub-admin.admin',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
