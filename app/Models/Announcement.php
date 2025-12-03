<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Announcement Model
 * 
 * Represents system announcements that can be targeted to specific user roles.
 * Supports scheduling, expiration, and role-based audience targeting.
 * 
 * @property int $id
 * @property string $title
 * @property string|null $type
 * @property string $body
 * @property array|null $audience (JSON array of roles)
 * @property string $status (draft, published, archived, scheduled)
 * @property \Illuminate\Support\Carbon|null $publish_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'body',
        'audience',
        'status',
        'publish_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'audience' => 'array',
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'published',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'userId');
    }

    public function scopePublished(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where('status', 'published')
            ->where(function ($inner) use ($now) {
                $inner->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $this->scopePublished($query);
    }

    public function scopeExpired(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->where(function ($q) use ($now) {
            $q->where('status', 'archived')
              ->orWhere(function ($inner) use ($now) {
                  $inner->whereNotNull('expires_at')->where('expires_at', '<', $now);
              });
        });
    }

    public function scopeVisibleForRole(Builder $query, string $role): Builder
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('audience')
              ->orWhereJsonContains('audience', $role);
        });
    }

    public function isActive(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'archived' => 'Archived',
            'scheduled' => 'Scheduled',
            'draft' => 'Draft',
            'published' => $this->isActive() ? 'Active' : 'Expired',
            default => ucfirst($this->status),
        };
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            'archived' => 'secondary',
            'scheduled' => 'warning',
            'draft' => 'secondary',
            'published' => $this->isActive() ? 'success' : 'secondary',
            default => 'secondary',
        };
    }
}

