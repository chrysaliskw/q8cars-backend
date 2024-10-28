<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuratedComparison extends Model
{
    use HasFactory;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    const FILE_DIR = 'curated-comparisons';

    protected $fillable = [
        'brand_id_1',
        'brand_id_2',
        'brand_id_3',
        'car_id_1',
        'car_id_2',
        'car_id_3',
        'title',
        'content',
        'source',
        'html_content',
        'image_1',
        'image_2',
        'image_3',
        'status',
        'published_date'
        
    ];

    /*
   |--------------------------------------------------------------------------
   | Local Scopes
   |--------------------------------------------------------------------------
   */
   public function scopeActive($query)
   {
       return $query->where('status', self::STATUS_ACTIVE);
   }

   public function car1()
   {
        return $this->belongsTo(Car::class, 'car_id_1');
   }
    
   public function brand1()
   {
        return $this->belongsTo(Brand::class, 'brand_id_1');
   }
   public function car2()
   {
        return $this->belongsTo(Car::class, 'car_id_2');
   }
   public function brand2()
   {
        return $this->belongsTo(Brand::class, 'brand_id_2');
   }
   public function car3()
   {
        return $this->belongsTo(Car::class, 'car_id_3');
   }
   public function brand3()
   {
        return $this->belongsTo(Brand::class, 'brand_id_3');
   }
}
