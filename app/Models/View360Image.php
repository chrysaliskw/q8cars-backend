<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View360Image extends Model
{
    use HasFactory;
    const FILE_DIR = '360-view';
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
