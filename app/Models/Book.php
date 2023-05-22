<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'published_year',
        'genre_id',
        'stock'
    ];

    /**
     * Get the related genre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function genre() : \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Genre::class, 'id', 'genre_id');
    }
}
