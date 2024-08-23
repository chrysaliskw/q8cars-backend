<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarVersion extends Model
{
    use HasFactory;

    const BASE_VARIENT = 1;
    const NOT_BASE_VARIENT = 2;

    const CAR_SPECIFICATION = 1;
    const CAR_VARIENT_SPECIFICATION = 2;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function news()
    {
        return $this->hasMany(News::class);  
    }
    public function bodyType()
    {
        return $this->belongsTo(BodyType::class, 'body_type');  
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeBaseVarient($query)
    {
        return $query->where('is_base_varient', self::BASE_VARIENT);
    }

    
}
