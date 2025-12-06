<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    // Show all e-books
    public function allEbooks()
    {
        $categories = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];

        $ebooksByCategory = Ebook::latest()->get()->groupBy('category');

        return view('faculty.all_ebooks', [
            'categories' => $categories,
            'ebooksByCategory' => $ebooksByCategory,
        ]);
    }

    // Show single e-book details
    public function show($id)
    {
        $ebook = Ebook::findOrFail($id);
        
        // Increment view count
        $ebook->increment('views');
        
        // If it's an AJAX request (from modal), return JSON
        if (request()->ajax()) {
            return response()->json([
                'views' => $ebook->fresh()->views
            ]);
        }
        
        return view('faculty.ebook_details', compact('ebook'));
    }
}
