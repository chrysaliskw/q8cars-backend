<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_1',
        'car_2',
        'car_3',
        'car_4',
    ];

    public function car1()
    {
        return $this->belongsTo(Car::class, 'car_1');
    }

    public function car2()
    {
        return $this->belongsTo(Car::class, 'car_2');
    }

    public function car3()
    {
        return $this->belongsTo(Car::class, 'car_3');
    }

    public function car4()
    {
        return $this->belongsTo(Car::class, 'car_4');
    }
}
