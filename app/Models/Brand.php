<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const TOP_BRAND = 1;
    const NOT_TOP_BRAND = 2;

    const RECENT_PURCHASED = 1;
    const NOT_RECENT_PURCHASED = 2;

    const FILE_DIR = 'brands';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
        'is_top_brand',
        'status'
    ];
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);  
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
