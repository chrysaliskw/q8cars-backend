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
    public function carAdditionalSpecifications()
    {
        return $this->hasMany(CarAdditonalSpecifications::class);
    }
    public function engine()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_ENGINE);
    }
    public function fuel()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_FUEL);
    }
    public function suspension()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_SUSPENSION);
    }
    public function dimension()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_DIMENSION);
    }
    public function exterior()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_EXTERIOR);
    }
    public function interior()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_INTERIOR);
    }
    public function safety()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_SAFETY);
    }
    public function entertainment()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_ENTERTAINMENT);
    }
    public function comfort()
    {
        return $this->hasMany(CarAdditonalSpecifications::class)->where('category_id', CarAdditonalSpecifications::CATEGORY_COMFORT);
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
