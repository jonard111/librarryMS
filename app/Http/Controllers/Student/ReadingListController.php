<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingListController extends Controller
{
    /**
     * Display the user's reading list
     */
    public function index()
    {
        $readingList = ReadingList::where('user_id', Auth::id())
            ->with('book')
            ->latest('created_at')
            ->paginate(12);
        
        return view('student.reading_list', compact('readingList'));
    }

    /**
     * Add a book to the reading list
     */
    public function add($bookId)
    {
        $book = Book::findOrFail($bookId);
        
        // Check if already in reading list
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
     * Remove a book from the reading list
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

