<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DEACTIVATED = 3;

    const ROLE_GUEST = 1;
    const ROLE_DEFAULT = 2;

    const FILE_DIR = 'users';

    const DEVICE_ANDROID = 1;
    const DEVICE_IOS = 2;

    const SESSION_LIMIT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'is_mute',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeRoleDefault($query)
    {
        return $query->where('role', self::ROLE_DEFAULT);
    }

    public function scopeRoleGuest($query)
    {
        return $query->where('role', self::ROLE_GUEST);
    }

    /**
     * @return \App\Models\User
     */
    public static function guestUser()
    {
        return self::find(0);
    }

    /**
     * @return bool
     */
    public function isNotGuest()
    {
        return $this->id != 0;
    }

    /**
     * @return array
     */
    public function loginResponseToApi($deviceName)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => str_replace($this->phone_code,'',$this->mobile),
            'picture' => $this->picture ? file_asset('files-user', $this->picture) : null,
            'access_token' => $this->createToken($deviceName)->plainTextToken,
            'is_guest' => false,
            "fcm_common_topic" => Notification::COMMON_CHANNEL,
            "fcm_individual_topic" => get_user_topic($this->id),
            'is_mute' => $this->is_mute,
            'is_notification' => UserNotificationMapping::where('user_id',$this->id)->where('read_status',Notification::NOT_READ)->exists(),
            'expires_in' => config('sanctum.expiration') ? config('sanctum.expiration') * 60 : null,
            
        ];
    }

    /**
     * @return array
     */
    public function profileResponseToApi()
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'mobile' => str_replace($this->phone_code,'',$this->mobile),
            'address' => !empty($this->address) ? $this->address : null,
            'picture' => $this->picture ? file_asset('files-user', $this->picture) : null,
            'role' => $this->role,
            'status' => $this->status,
            'is_guest' => false,
            'is_mute' => $this->is_mute,
            "fcm_common_topic" => Notification::COMMON_CHANNEL,
            "fcm_individual_topic" => get_user_topic($this->id),
           'is_notification' => UserNotificationMapping::where('user_id',$this->id)->where('read_status',Notification::NOT_READ)->exists(),
        ];
    }

    /**
     * Clear the session to limit the mobile sesison to the value of SESSION_LIMIT.
     *
     * @return void
     */
    public function clearMobileSessions()
    {
        $tokensCount = $this->tokens()->count();

        if ($tokensCount >= (self::SESSION_LIMIT)) {
            $this->tokens()->limit($tokensCount - (self::SESSION_LIMIT - 1))->delete();
        }
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notification_mappings')
            ->withPivot('read_status')
            ->withTimestamps();
    }
    public function offerRequests()
    {
        return $this->hasMany(OfferRequest::class);
    }
}
