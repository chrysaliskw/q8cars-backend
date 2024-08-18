<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_PENDING = 3;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function brand()
    {
        return $this->belongsTo(Brand::class);  
    }
    public function car()
    {
        return $this->belongsTo(Car::class);  
    }
    public function carVersion()
    {
        return $this->belongsTo(CarVersion::class);  
    }
     /*
     |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('question_status', self::STATUS_ACTIVE);
    }
}
