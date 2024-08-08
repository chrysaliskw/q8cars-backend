<?php

use App\Models\Admin;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\User;

return [

    'files' => [
        'admin' => Admin::FILE_DIR,
        'user' => User::FILE_DIR,
        'brand' => Brand::FILE_DIR,
        'body-type' => BodyType::FILE_DIR,  
    ],

    'user' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
    ],
   
];
