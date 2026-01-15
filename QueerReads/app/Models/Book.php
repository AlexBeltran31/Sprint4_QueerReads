<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'publication_year', 'cover_image'];

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_book');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_books')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
