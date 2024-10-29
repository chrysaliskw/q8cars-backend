<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSuggestionRequest extends Model
{
    use HasFactory;

    const STATUS_SUBMITTED = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_REJECTED = 3;

    const STATUSES = [
        self::STATUS_SUBMITTED => 'Submitted',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Rejected'
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'civil_id',
        'email',
        'bank_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
