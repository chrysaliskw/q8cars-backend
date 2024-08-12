<?php

use App\Models\Admin;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Car;
use App\Models\User;

return [

    'files' => [
        'admin' => Admin::FILE_DIR,
        'user' => User::FILE_DIR,
        'brand' => Brand::FILE_DIR,
        'body_type' => BodyType::FILE_DIR,  
        'car' => Car::FILE_DIR,
    ],

    'user' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
    ],
    'brand' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
        'is_top_brand' => [
            1 => 'Yes',
            2 => 'No'
        ],
    ],
    'body-type' =>  [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
    ],
   
];
