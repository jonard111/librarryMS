<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReadingListController
 * 
 * Manages student reading list (wishlist) functionality:
 * - View all saved books
 * - Add books to reading list
 * - Remove books from reading list
 * - Prevents duplicate entries
 */
class ReadingListController extends Controller
{
    /**
     * Display the user's complete reading list
     * Shows paginated list of all books saved by the student
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all reading list items for current user, paginated (12 per page)
        $readingList = ReadingList::where('user_id', Auth::id())
            ->with('book') // Eager load book relationship
            ->latest('created_at') // Most recently added first
            ->paginate(12);
        
        return view('student.reading_list', compact('readingList'));
    }

    /**
     * Add a book to the student's reading list
     * 
     * Process:
     * 1. Verify book exists
     * 2. Check if already in reading list (prevent duplicates)
     * 3. Create reading list entry
     * 
     * @param int $bookId Book ID to add
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($bookId)
    {
        $book = Book::findOrFail($bookId);
        
        // STEP 1: Check if already in reading list (prevent duplicates)
        $existing = ReadingList::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();
        
        if ($existing) {
            return back()->with('info', 'This book is already in your reading list.');
        }
        
        ReadingList::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
        ]);
        
        return back()->with('success', '"' . $book->title . '" has been added to your reading list.');
    }

    /**
     * Remove a book from the student's reading list
     * 
     * Process:
     * 1. Find reading list entry
     * 2. Delete entry if found
     * 
     * @param int $bookId Book ID to remove
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($bookId)
    {
        $readingListItem = ReadingList::where('user_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();
        
        if ($readingListItem) {
            $bookTitle = $readingListItem->book->title ?? 'Book';
            $readingListItem->delete();
            
            return back()->with('success', '"' . $bookTitle . '" has been removed from your reading list.');
        }
        
        return back()->with('error', 'Book not found in your reading list.');
    }
}

