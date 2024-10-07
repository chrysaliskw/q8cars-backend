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
    CONST SHOW_IN_SUGGESTIONS = 1;
    const FILE_DIR = 'offers';

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

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    protected $fillable = [
        'car_id',
        'brand_id',
        'car_version_id',
        'title',
        'image',
        'key_feature_1',
        'key_icon_1',
        'key_feature_2',
        'key_icon_2',
        'description',
        'html_description',
        'offer',
        'start_date',
        'end_date',
        'status',
        'view_count',
        'show_in_suggestions'
    ];

}
