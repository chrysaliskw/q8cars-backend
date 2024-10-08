<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'image',
        'link_to',
        'logo',
        'business_name',
        'start_date',
        'end_date',
        'status',
    ];

    public function userMappings(){
        return $this->hasMany(UserNotificationMapping::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_notification_mappings')
                    ->withPivot('read_status')
                    ->withTimestamps();
    }
}
