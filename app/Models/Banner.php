<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    const PAGE_HOME = 1;
    const MAX_NUM_OF_PAGE_HOME = 10;     //Cr - No limit for directory home banner

    const TYPE_CAR = 1;
    const TYPE_NEWS = 2;
    const TYPE_OFFERS = 3;

    const DOC_DIR = 'banners';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function car()
    {
        return $this->belongsTo(Car::class, 'link_to');
    }

     /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('banners.status', self::STATUS_ACTIVE);
    }
}
