<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Ebook Model
 * 
 * Represents a digital book (eBook) in the library system.
 * 
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string|null $category
 * @property string|null $file_path
 * @property string|null $cover_path
 * @property int $views
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category',
        'file_path',
        'cover_path',
        'views',
    ];

    protected $casts = [
        'views' => 'integer',
    ];

    /**
     * Get the URL for the ebook cover image
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

    /**
     * Get the URL for the ebook file
     * 
     * @return string|null
     */
    public function fileUrl(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        
        // Check if file exists
        if (!Storage::disk('public')->exists($this->file_path)) {
            return null;
        }
        
        // Generate URL - use url() helper for absolute URL
        return url('storage/' . $this->file_path);
    }
}
