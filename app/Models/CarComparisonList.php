<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarComparisonList extends Model
{
    use HasFactory;

    const HOME_PAGE = 1;
    const CAR_DETAIL_PAGE = 2;
    const CAR_COMPARISON_PAGE = 3;

    protected $fillable = [
        'car_1_id',
        'car_2_id',
        'car_version_1_id',
        'car_version_2_id',
        'body_type',
        'page',
        'brand_id',
        'brand_1_id',
        'brand_2_id',
        'car_id'
    ];
    public function car1()
    {
        return $this->belongsTo(Car::class, 'car_1_id');
    }
    public function car2()
    {
        return $this->belongsTo(Car::class, 'car_2_id');
    }
    public function carVersion1()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_1_id');
    }
    public function carVersion2()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_2_id');
    }
    public function bodyType()
    {
        return $this->belongsTo(BodyType::class, 'body_type');
    }
    public function brand1()
    {
        return $this->belongsTo(Brand::class, 'brand_1_id');
    }
    public function brand2()
    {
        return $this->belongsTo(Brand::class, 'brand_2_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function version1()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_1_id');
    }
    public function version2()
    {
        return $this->belongsTo(CarVersion::class, 'car_version_2_id');
    }
}
