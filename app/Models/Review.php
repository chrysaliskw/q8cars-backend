<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    const STATUS_VERIFIED = 1;
    const STATUS_SUBMITTED = 2;
    const STATUS_REJECTED = 3;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);  
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
        return $query->where('status', self::STATUS_VERIFIED);
    }
}
