<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'comments',
        'checkout_date',
        'return_date',
    ];

    /**
     * Get the related user
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related book
     *
     * @return BelongsTo
     */
    public function book() : BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
