<?php

use App\Models\Admin;
use App\Models\Bank;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CuratedComparison;
use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use App\Models\View360Image;

return [

    'files' => [
        'admin' => Admin::FILE_DIR,
        'user' => User::FILE_DIR,
        'brand' => Brand::FILE_DIR,
        'body_type' => BodyType::FILE_DIR,
        'car' => Car::FILE_DIR,
        'news' => News::FILE_DIR,
        'notifications' => Notification::FILE_DIR,
        'curated_comparisons' => CuratedComparison::FILE_DIR,
        'banks' => Bank::FILE_DIR,
        '360_view' =>View360Image::FILE_DIR,
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

    'review' => [
        'status' => [
            1 => 'Verified',
            2 => 'Submitted',
            3 => 'Rejected',
        ],
    ],

    'car' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
        'fuel_type' => [
            1 => 'Petrol',
            2 => 'Diesel',
            3 => 'CNG',
            4 => 'Electric',
        ],
        'transmission_type' => [
            1 => 'Automatic',
            2 => 'Manual',
            3 => 'Clutchless Manual',
            4 => 'Automatic -TC',
        ],
        'image-section' =>  [
            1 => 'Exterior',
            2 => 'Interior',
            3 => 'Gears, Pedals and Stalks',
            4 => 'Seat & seat adjustments',
            5 => '360 view',
        ],
        'specification-section' => [
            1 => 'Engine and Transmission',
            2 => 'Fuel and Performance',
            3 => 'Suspension, Steering and Brake',
            4 => 'Dimension Capacity',
            5 => 'Comfort Convinience',
            6 => 'Interior',
            7 => 'Exterior',
            8 => 'Safety',
            9 => 'Entertainment and Comminication',
            10 =>  'Key Specification',
            11 => 'Key Features',
        ],
        'view-camera' => [
            1 => 'Yes',
            2 => 'No',
        ],
        'travel_type' => [
            1 => 'Family Trips',
            2 => 'Solo',
            3 => 'Road Trips',
            4 => 'Office Drive',
            5 => 'Others',
        ]
    ],

    'news' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
            3 => 'Expired',
        ],
        'is_trending' => [
            1 => 'Yes',
            2 => 'No',
        ],
    ],

    'colors' => [
        1 => 'Cyan',
        2 => 'Green',
        3 => 'Carpathian Grey',
        4 => 'Firenze Red',
        5 => 'Blue',
        6 => 'White',
        7 => 'Black',
        8 => 'Yellow',
        9 => 'Red',
        10 => 'Grey',
        11 => 'Purple',
        12 => 'Brown',
        13 => 'Silver',
        14 => 'Orange',
        15 => 'Beige',
        16 => 'Gold',
        17 => 'Bronze',
        18 => 'Copper',
    ],
    'brand_color' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
    ],
    'test_drive' => [
        'status' => [
            1 => 'Submitted',
            2 => 'Ongoing',
            3 => 'Completed',
            4 => 'Cancelled',
            5 => 'Not Verified',
            6 => 'Rejected',
        ],
    ],
    'offer_request' => [
        'type' => [
            1 => 'Offer Request',
            2 => 'On Road Price Request',
            3 => 'EMI Request',
        ],
        'status' => [
            1 => 'Pending',
            2 => 'Completed',
            3 => 'Cancelled',

        ]
    ],
    'professions' => [
        1 => 'Doctor',
        2 => 'Engineer',
        3 => 'Designer',
        4 => 'Architect',
        5 => 'Others',
    ],
    'faq' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ],
    ],
    'offers' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
            3 => 'Expired',
        ],
        'show_in_suggestions' => [
            1 => 'Yes',
            2 => 'No',
        ],
    ],
    'car-comparison-list' => [
        'page' => [
            1 => 'Home Page',
            2 => 'Detailed Page',
            3 => 'Car Comparison Page',
        ]
    ],
    'curated-comparisons' => [
        'status' => [
            1 => 'Active',
            2 => 'Inactive',
        ]
    ],
    'banks' => [
        'status' => [
            1 => 'Submitted',
            2 => 'Accepted',
            3 => 'Rejected',
        ]
    ],
    'sub-admin' => [
        'sections' => [
            'Dashboard' => 'Dashboard',
            'Users' => 'Users',
            'Brands' => 'Brands',
            'Body Types' => 'Body Types',
            'Colors' => 'Colors',
            'Emi Calculator' => 'Emi Calculator',
            'Loan Eligibility Calculator' => 'Loan Eligibility Calculator',
            'Car Management' => 'Car Management',
            'Test Drive Requests' => 'Test Drive Requests',
            'Offers' => 'Offers',
            'Offers Requests' => 'Offers Requests' ,
            'Notifications' => 'Notifications' ,
            'Banks' => 'Banks',
            'Reviews' => 'Reviews',
            'Faq' => 'Faq',
            'News' => 'News',
            'Reports' => 'Reports',
        ]
    ],
];
