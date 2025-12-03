<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Book Model
 * 
 * Represents a physical book in the library system.
 * 
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string|null $isbn
 * @property string|null $publisher
 * @property string|null $category
 * @property int $copies
 * @property string|null $cover_path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'category',
        'copies',
        'cover_path',
    ];

    protected $casts = [
        'copies' => 'integer',
    ];

    /**
     * Get all reservations for this book
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(BookReservation::class);
    }

    /**
     * Get the URL for the book cover image
     * 
     * @return string|null
     */
    public function coverUrl(): ?string
    {
        if (!$this->cover_path) {
            return null;
        }
        
        // Check if file exists
        if (!Storage::disk('public')->exists($this->cover_path)) {
            return null;
        }
        
        // Generate URL - use url() helper for absolute URL or asset() for relative
        // This ensures the URL works regardless of APP_URL setting
        return url('storage/' . $this->cover_path);
    }
}
