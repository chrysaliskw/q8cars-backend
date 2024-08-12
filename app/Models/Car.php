<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    const FILE_DIR = 'cars';

    public function brand()
    {
        return $this->belongsTo(Brand::class);  
    }
}
