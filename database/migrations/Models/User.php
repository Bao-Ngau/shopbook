<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'users';
    // protected $fillable = [
    //     'name_user',
    //     'email',
    //     'email_code',
    //     'refesh_token',
    //     'password',
    //     'coupon_id',
    //     'role_id',
    //     'address',
    //     'phone',
    //     'status_user',
    //     'created_date_user',
    // ];
    protected $primaryKey = 'id';
    protected $hidden = [
        'password',
        'refesh_token',
    ];
    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
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
