<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 * @property int $loan_duration
 * @property string $loan_duration_unit (day, hour)
 * @property float $fine_amount
 * @property \Illuminate\Support\Carbon|null $fine_paid_at
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
        'loan_duration',
        'loan_duration_unit',
        'fine_amount',
        'fine_paid_at',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'pickup_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'loan_duration' => 'integer',
        'fine_amount' => 'decimal:2',
        'fine_paid_at' => 'datetime',
    ];

    private const FINE_RATE_PER_DAY = 5.00;

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

    /**
     * Format the requested loan duration for display.
     */
    public function getLoanDurationLabelAttribute(): string
    {
        $value = $this->loan_duration ?? 0;
        $unit = $this->loan_duration_unit ?? 'day';

        $unitLabel = $unit === 'hour' ? 'hour' : 'day';

        return sprintf('%d %s', $value, \Illuminate\Support\Str::plural($unitLabel, $value));
    }

    /**
     * Determine if the reservation is currently overdue.
     */
    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->return_date) {
            return false;
        }

        return Carbon::parse($this->due_date)->isPast();
    }

    /**
     * Accessor for the is_overdue attribute.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }

    /**
     * Determine if there is an outstanding fine that has not been settled.
     */
    public function hasUnsettledFine(): bool
    {
        return $this->isOverdue() && $this->fine_paid_at === null;
    }

    /**
     * Accessor for the has_unsettled_fine attribute.
     */
    public function getHasUnsettledFineAttribute(): bool
    {
        return $this->hasUnsettledFine();
    }

    /**
     * Calculate the current fine amount based on days overdue.
     */
    public function calculateFine(): float
    {
        if (!$this->isOverdue()) {
            return 0.0;
        }

        $overdueDays = Carbon::parse($this->due_date)->diffInDays(now());

        return round($overdueDays * self::FINE_RATE_PER_DAY, 2);
    }

    /**
     * Accessor for the current_fine attribute.
     */
    public function getCurrentFineAttribute(): float
    {
        return $this->calculateFine();
    }
}
