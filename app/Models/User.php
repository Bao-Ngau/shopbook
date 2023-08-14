<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_user',
        'email',
        'image_user',
        'email_code',
        'refresh_token',
        'password',
        'coupon_id',
        'role_id',
        'address',
        'phone',
        'status_user',
        'created_date_user',
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'refesh_token',
        'email_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            // 'user_data' => array_merge($this->toArray(), ['role_id' => $this->role]),
        ];
    }
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'user_id', 'id');
    }
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'user_id', 'id');
    }
    public function coupon(): HasMany
    {
        return $this->hasMany(Coupon::class, 'id', 'coupon_id');
    }
}
