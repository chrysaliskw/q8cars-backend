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
