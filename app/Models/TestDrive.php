<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestDrive extends Model
{
    use HasFactory;

    const STATUS_SUBMITTED = 1;
    const STATUS_ONGOIND = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_NOT_VERIFIED = 5;
    const STATUS_REJECTED = 6;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
