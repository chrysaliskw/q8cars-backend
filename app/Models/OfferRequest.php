<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferRequest extends Model
{
    use HasFactory;

    const TYPE_OFFER = 1;
    const TYPE_ONROAD_PRICE = 2;
    const TYPE_EMI_OFFER = 3;
    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
