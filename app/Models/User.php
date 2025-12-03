<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * User Model
 * 
 * Represents a user in the library management system.
 * Supports multiple roles: student, faculty, headlibrarian, assistant, admin
 * 
 * @property int $userId
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $role (student, faculty, headlibrarian, assistant, admin)
 * @property string $account_status (pending, approved, rejected)
 * @property \Illuminate\Support\Carbon $registration_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userId';

    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'password',
        'account_status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'registration_date' => 'datetime',
    ];

    /**
     * Get all book reservations for this user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(BookReservation::class, 'user_id', 'userId');
    }

    /**
     * Get all announcements created by this user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by', 'userId');
    }

    /**
     * Get the user's full name
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user account is approved
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->account_status === 'approved';
    }
}
