<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const TR_AUTOMATIC = 1;
    const TR_MANUAL = 2;
    const TR_CLUCHLESS_MANUAL = 3;
    const TR_AUTOMATIC_TC = 4;

    const JUST_LAUNCHED = 1;
    const NOT_JUST_LAUNCHED = 2;

    const FILE_DIR = 'cars';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function brand()
    {
        return $this->belongsTo(Brand::class);  
    }
    public function carVersions()
    {
        return $this->hasMany(CarVersion::class);  
    }
    public function manualVersion()
    {
        return $this->hasOne(CarVersion::class)->where('transmission_type', Car::TR_MANUAL)->first();  
    }
    public function automaticVersion()
    {
        return $this->hasOne(CarVersion::class)->where('transmission_type', Car::TR_AUTOMATIC)->first();  
    }
    public function news()
    {
        return $this->hasMany(News::class);  
    }
    public function carImages()
    {
        return $this->hasMany(CarImage::class);
    }
    public function carPhotos()
    {
        return $this->hasMany(CarImage::class)->where('type', CarImage::TYPE_IMAGE);
    }
    public function carVideos()
    {
        return $this->hasMany(CarImage::class)->where('type', CarImage::TYPE_VIDEO);
    }
    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
   
}
