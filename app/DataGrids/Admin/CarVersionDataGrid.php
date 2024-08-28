<?php

namespace App\DataGrids\Admin;

use App\Models\Car;
use App\Models\CarVersion;
use Rufaidulk\DataGrid\Grid;

class CarVersionDataGrid extends Grid
{
    public $wrapperClass = 'table-responsive';
    public $showFilters = false;

    public function gridQuery()
    {
        
        $query = CarVersion::query() 
            ->when(request()->query('car_id'), function ($query, $value){
                $query->where('car_versions.car_id', $value);
            })
            ->where('is_car_spec', CarVersion::CAR_VARIENT_SPECIFICATION)
            ->select(['car_versions.*']);

        return $query;
    }

    public function columns()
    {
        return [
            'varient_name' => [
                'label' => 'Version Name',
                'value' => function ($model) {
                    return $model->varient_name;
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'varient_name',
                ]
            ],
            'engine_capacity'=> [
                'label' => 'Engine Capacity',
                'value' => function ($model) {
                    return $model->engine_capacity . ' cc';
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'engine_capacity',
                ]
            ],
            'transmission_type'=> [
                'label' => 'Transmission Type',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'transmission_type',
                    'operator' => '=',
                    'data' => config('params.car.transmission_type')
                ],
                'value' => function ($model) {
                    return config('params.car.transmission_type')[$model->transmission_type];
                },
            ],
            'fuel_type'=> [
                'label' => 'Fuel Type',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'fuel_type',
                    'operator' => '=',
                    'data' => config('params.car.fuel_type')
                ],
                'value' => function ($model) {
                    return config('params.car.fuel_type')[$model->fuel_type];
                },
            ],
            'mileage'=> [
                'label' => 'Mileage',
                'value' => function ($model) {
                    return $model->mileage . ' km/L';
                },
                'filter' => true,
                'filterOptions' => [
                    'type' => 'text',
                    'attribute' => 'mileage',
                ]
            ],

            'status' => [
                'label' => 'Status',
                'filter' => true,
                'filterOptions' => [
                    'type' => 'select',
                    'attribute' => 'status',
                    'operator' => '=',
                    'data' => config('params.car.status')
                ],
                'value' => function ($model) {
                    return config('params.car.status')[$model->status];
                },
            ],

            'action' => [
                'routePrefix' => 'admin.car-version',
                'contentCssClass' => 'grid-action-col',
            ]
        ];
    }
}
