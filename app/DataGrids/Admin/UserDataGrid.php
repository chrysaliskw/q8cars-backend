<?php

namespace App\DataGrids\Admin;

use App\Models\User;
use Rufaidulk\DataGrid\Grid;
use Illuminate\Support\Facades\DB;

class UserDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';

    public function gridQuery()
    {
        $query = User::query() 
        ->where('id' ,'!=' ,0)->orderBy('id','Desc')
        ->select(['users.*', DB::raw("CONCAT(users.phone_code, users.mobile) as full_mobile")]);
   
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
                        return "<img src='{$url}' alt='body-type-img' class='img-thumbnail img-list'>";
                    } else {
                        return '<img src="' . asset('moltran-asset/images/dp.png') . '" alt="profile-img" class="img-thumbnail img-list">';
                    }
                }
            ],
            'mobile' => [
                'label' => 'Mobile',
                'value' => function ($model) {
                    return $model->phone_code .$model->mobile;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'mobile',
                ]
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
                'routePrefix' => 'admin.user', 
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
