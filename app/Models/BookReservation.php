<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * BookReservation Model
 * 
 * Represents a book reservation/borrowing record in the library system.
 * 
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property string $status (pending, approved, rejected, picked_up, returned, cancelled)
 * @property \Illuminate\Support\Carbon $reservation_date
 * @property \Illuminate\Support\Carbon|null $pickup_date
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $return_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class BookReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'reservation_date',
        'pickup_date',
        'due_date',
        'return_date',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'pickup_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user who made this reservation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }

    /**
     * Get the book for this reservation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
