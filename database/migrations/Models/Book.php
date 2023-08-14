<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'book_id', 'id');
    }
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'book_id', 'id');
    }
    public function author(): HasMany
    {
        return $this->hasMany(Author::class, 'id', 'author_id');
    }
    public function category(): HasMany
    {
        return $this->hasMany(Category::class, 'id', 'category');
    }
}
