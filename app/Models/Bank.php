<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    const FILE_DIR = 'banks';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const STATUSES = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    protected $fillable = [
        'bank_name',
        'branch_name',
        'city',
        'status',
        'logo',
        // 'base_gross_income',
        // 'base_other_emi',
        // 'base_interest_rate',
        'eligible_emi_percentage',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
