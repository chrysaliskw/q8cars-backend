<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_EXPIRED = 3;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function carVersion()
    {
        return $this->belongsTo(CarVersion::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

}
