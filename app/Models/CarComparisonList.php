<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarComparisonList extends Model
{
    use HasFactory;

    const HOME_PAGE = 1;
    const CAR_DETAIL_PAGE = 2;

    public function car1()
    {
        return $this->belongsTo(Car::class,'car_1_id');
    }
    public function car2()
    {
        return $this->belongsTo(Car::class,'car_2_id');
    }
    public function carVersion1()
    {
        return $this->belongsTo(CarVersion::class,'car_version_1_id');
    }
    public function carVersion2()
    {
        return $this->belongsTo(CarVersion::class,'car_version_2_id');
    }
}
