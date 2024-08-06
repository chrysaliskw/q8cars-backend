<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DEACTIVATED = 3;

    const ROLE_GUEST = 1;
    const ROLE_DEFAULT = 2;
    
    const IMAGE_DIR = 'users';

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
            'mobile' => $this->mobile,
            // 'picture' => file_asset('files-user', $this->picture),
            'access_token' => $this->createToken($deviceName)->plainTextToken,
            'is_guest' => false,
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
            'email' => $this->email,
            'mobile' => $this->mobile,
            // 'picture' => file_asset('files-user', $this->picture),
            'is_guest' => false,
        ];
    }
}
