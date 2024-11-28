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

    const TYPE_BANK = 1;
    const TYPE_LOAN = 2;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'contact_number',
        'civil_id',
        'email',
        'bank_name',
        'type',
        'bank_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
