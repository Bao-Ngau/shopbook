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
    protected $fillable = [
        'name_book',
        'image_book',
        'description',
        'advantage',
        'author_id',
        'category_id',
        'count_book',
        'price',
        'sale',
        'price_sale',
        'status_book',
        'create_date_book',
        'create_by_book',
        'updated_date_book',
        'updated_at_book',
    ];
    public $timestamps = false;

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'id', 'book_id');
    }
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'book_id', 'id');
    }
    public function author(): HasMany
    {
        return $this->hasMany(Author::class, 'id', 'author_id');
    }
    public function categoryy(): HasMany
    {
        return $this->hasMany(Category::class, 'id', 'category_id');
    }
}
