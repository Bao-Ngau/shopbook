<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'book_id',
        'user_id',
        'quantity',
        'status_cart',
        'pay_id',
        'total_money',
        'created_date_cart',
        'created_by_cart',
    ];
    public $timestamps = false;

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function pay(): HasOne
    {
        return $this->hasOne(Pay::class, 'id', 'pay_id');
    }
    public function book(): HasMany
    {
        return $this->hasMany(Book::class, 'id', 'book_id');
    }
}
