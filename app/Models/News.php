<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_EXPIRED = 3;

    const PUBLISHED = 1;
    const NOT_PUBLISHED = 2;

    const TRENDING = 1;
    const NOT_TRENDING = 2;

    const FILE_DIR = 'news';
;
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function car()
    {
        return $this->belongsTo(Car::class);
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
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopePublished($query)
    {
        return $query->where('is_published', self::PUBLISHED);
    }
    public function scopeTrending($query)
    {
        return $query->where('is_trending', self::TRENDING);
    }
}
