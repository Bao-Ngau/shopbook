<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function book(): HasMany
    {
        return $this->hasMany(Book::class, 'id', 'book_id');
    }
}
