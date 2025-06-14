<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',

       
        'mobile',
        'otp',
        'role',
    ];


    protected $attributes = [
        'otp' => 0,
        'role' => 'shopKeeper',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            // 'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            // Force default values if not provided
            if (is_null($user->otp)) {
                $user->otp = 0;
            }
            if (is_null($user->role)) {
                $user->role = 'shopKeeper';
            }
        });
    }
}
