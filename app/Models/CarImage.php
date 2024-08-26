<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;

    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function car()
    {
        return $this->belongsTo(Car::class);  
    }
    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeImage($query)
    {
        return $query->where('type', self::TYPE_IMAGE);
    }
    public function scopeVideo($query)
    {
        return $query->where('type', self::TYPE_VIDEO);
    }
}
