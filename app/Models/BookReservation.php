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

    /**
     * Fine rates for overdue books
     * - Hourly loans: ₱1.00 per hour overdue
     * - Daily loans: ₱10.00 per day overdue
     */
    private const FINE_RATE_PER_HOUR = 1.00;
    private const FINE_RATE_PER_DAY = 10.00;

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
     * 
     * A book is overdue if:
     * 1. It has a due_date
     * 2. It has been picked up (status is 'picked_up')
     * 3. It has NOT been returned yet (return_date is null)
     * 4. The due_date is in the past
     */
    public function isOverdue(): bool
    {
        // Must have a due date
        if (!$this->due_date) {
            return false;
        }
        
        // Must be picked up (not just approved)
        if ($this->status !== 'picked_up') {
            return false;
        }
        
        // Must not be returned yet
        if ($this->return_date) {
            return false;
        }

        // Check if due date is in the past
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
     * 
     * A fine is unsettled if:
     * 1. The book is overdue (past due date, not returned)
     * 2. The fine has not been paid yet (fine_paid_at is null)
     */
    public function hasUnsettledFine(): bool
    {
        // Book must be overdue first
        if (!$this->isOverdue()) {
            return false;
        }
        
        // Fine must not have been paid yet
        return $this->fine_paid_at === null;
    }

    /**
     * Accessor for the has_unsettled_fine attribute.
     */
    public function getHasUnsettledFineAttribute(): bool
    {
        return $this->hasUnsettledFine();
    }

    /**
     * Calculate the current fine amount based on overdue time
     * 
     * Formula:
     * - Hourly loans: (Hours Overdue) × ₱1.00
     * - Daily loans: (Days Overdue) × ₱10.00
     * 
     * Only calculates if book is overdue and not yet returned
     * 
     * @return float Fine amount in pesos
     */
    public function calculateFine(): float
    {
        // No fine if not overdue or already returned
        if (!$this->isOverdue()) {
            return 0.0;
        }

        // Get loan duration unit (default to 'day' if not set)
        $loanUnit = $this->loan_duration_unit ?? 'day';
        
        // Parse due date and current date/time
        $dueDate = Carbon::parse($this->due_date);
        $now = Carbon::now();
        
        if ($loanUnit === 'hour') {
            // Calculate hours overdue for hourly loans
            $overdueHours = $dueDate->diffInHours($now, false);
            
            // If due date is in the past, ensure at least 1 hour overdue
            if ($overdueHours <= 0 && $dueDate->isPast()) {
                $overdueHours = max(1, $now->diffInHours($dueDate));
            }
            
            // Safety check: if still 0 or negative, set to 1
            if ($overdueHours <= 0) {
                $overdueHours = 1;
            }
            
            // Return fine: hours × ₱1.00 per hour
            return round($overdueHours * self::FINE_RATE_PER_HOUR, 2);
        } else {
            // Calculate days overdue for daily loans
            $dueDateStart = $dueDate->copy()->startOfDay();
            $nowStart = $now->copy()->startOfDay();
            
            $overdueDays = $dueDateStart->diffInDays($nowStart, false);
            
            // If the due date is in the past, ensure at least 1 day overdue
            if ($overdueDays <= 0 && $dueDateStart->isPast()) {
                $overdueDays = max(1, $nowStart->diffInDays($dueDateStart));
            }
            
            // Safety check: if still 0 or negative, set to 1
            if ($overdueDays <= 0) {
                $overdueDays = 1;
            }
            
            // Return fine: days × ₱10.00 per day
            return round($overdueDays * self::FINE_RATE_PER_DAY, 2);
        }
    }

    /**
     * Accessor for the current_fine attribute.
     */
    public function getCurrentFineAttribute(): float
    {
        return $this->calculateFine();
    }
}
