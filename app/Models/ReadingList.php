<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ReadingList Model
 * 
 * Represents a book saved to a user's reading list (wishlist).
 * 
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ReadingList extends Model
{
    use HasFactory;

    protected $table = 'reading_lists';

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    /**
     * Get the user who added this book to their reading list
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    /**
     * Get the book in this reading list entry
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}

