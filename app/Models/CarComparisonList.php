<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarComparisonList extends Model
{
    use HasFactory;

    const HOME_PAGE = 1;
    const CAR_DETAIL_PAGE = 2;

    // public function brand()
    // {
    //     return $this->belongsTo(Brand::class);  
    // }
    // public function car()
    // {
    //     return $this->belongsTo(Car::class);  
    // }
    public function carVersion()
    {
        return $this->belongsTo(CarVersion::class);  
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function car1()
    {
        return $this->belongsTo(Car::class, 'car_1_id');
    }

    public function car2()
    {
        return $this->belongsTo(Car::class, 'car_2_id');
    }

    public function version1()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_1_id');
    }

    public function version2()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_2_id');
    }

    protected $fillable = [
        'page',
        'car_id',
        'car_1_id', 
        'car_version_1_id',
        'car_2_id',
        'car_version_2_id',
    ];
    
}
