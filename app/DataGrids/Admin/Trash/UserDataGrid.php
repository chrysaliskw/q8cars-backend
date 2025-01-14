<?php

namespace App\DataGrids\Admin\Trash;

use App\Models\User;
use Rufaidulk\DataGrid\Grid;

class UserDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = User::onlyTrashed()
            ->select(['*'])->orderBy('updated_at', 'desc');
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
                        $url = file_asset('files-user', $model->picture);
                        return "<img src='{$url}' alt='body-type-img' class='img-thumbnail img-list' >";
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
                    'attribute' => 'name',
                ]
            ],
            'email' => [
                'label' => 'Email',
                'value' => function ($model) {
                    return $model->email;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'email',
                ]
            ],
            'mobile' => [
                'label' => 'Mobile',
                'value' => function ($model) {
                    return $model->mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'mobile',
                ]
            ],
    
            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'users.status',
                    'operator' => '=',
                    'data' => config('params.user.status')
                ],
                'value' => function ($model) {
                    return config('params.user.status')[$model->status];
                },
            ],
            'action' => [
                'routePrefix' => 'admin.trash-user',
                'contentCssClass' => 'grid-action-col',
                'buttons' => ['view','restore'],
                'restore' => function($model){
                    $btn = "<a onclick='restoreUser(this)' data-id='{$model->id}' class='btn btn-pink waves-effect waves-light m-b-5 mr-1' title='Restore'>";
                    $btn .= "<span class='fa fa-undo'></span></a>";

                    return $btn;
                }
            ]
        ];
    }

    private function statusList()
    {
        $status = config('params.user.status');

        return $status;
    }
}
