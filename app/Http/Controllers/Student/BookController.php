<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    // Show student dashboard books
    public function index()
    {
        // Get most popular/recent books (limit to 6 for display)
        $popularBooks = Book::latest()->take(6)->get();
        
        // Get most popular/recent ebooks (limit to 6 for display)
        $popularEbooks = Ebook::latest()->take(6)->get();
        
        return view('student.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    }

    // Show all books
    public function allBooks()
    {
        $categories = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];

        $booksByCategory = Book::latest()->get()->groupBy('category');

        return view('student.all_books', [
            'categories' => $categories,
            'booksByCategory' => $booksByCategory,
        ]);
    }

    // Show single book details
    public function show($id)
    {
        $book = Book::findOrFail($id);
        $hasReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->exists();
        
        return view('student.book_details', compact('book', 'hasReservation'));
    }

    // Reserve a book
    public function reserve(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        // Check if user already has a pending/active reservation for this book
        $existingReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->first();
        
        if ($existingReservation) {
            return back()->with('error', 'You already have an active reservation for this book.');
        }
        
        // Check if book has available copies
        $reservedCopies = BookReservation::where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->count();
        
        if ($reservedCopies >= $book->copies) {
            return back()->with('error', 'No copies available for reservation at the moment.');
        }
        
        BookReservation::create([
            'user_id' => Auth::id(),
            'book_id' => $id,
            'status' => 'pending',
            'reservation_date' => now(),
        ]);
        
        return back()->with('success', 'Book reservation requested successfully!');
    }

    // Show borrowed books and requests
    public function borrowedBooks()
    {
        $userId = Auth::id();
        
        // Get borrowed books (picked_up or returned status)
        $borrowedBooks = BookReservation::where('user_id', $userId)
            ->whereIn('status', ['picked_up', 'returned'])
            ->with('book')
            ->latest('pickup_date')
            ->get();
        
        // Get book requests (pending, approved, picked_up)
        $bookRequests = BookReservation::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->with('book')
            ->latest('reservation_date')
            ->get();
        
        // Calculate statistics
        $borrowedBooksCount = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->count();
        
        $pendingRequests = BookReservation::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        
        // Calculate penalties (for returned books that were late)
        $penalties = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->whereNotNull('due_date')
            ->whereColumn('return_date', '>', 'due_date')
            ->count();
        
        $approvedBooks = BookReservation::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();
        
        return view('student.borrowed_books', [
            'borrowedBooks' => $borrowedBooks,
            'bookRequests' => $bookRequests,
            'borrowedBooksCount' => $borrowedBooksCount,
            'pendingRequests' => $pendingRequests,
            'penalties' => $penalties,
            'approvedBooks' => $approvedBooks,
        ]);
    }

    // Cancel a book request
    public function cancelRequest($id)
    {
        $reservation = BookReservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->whereIn('status', ['pending', 'approved'])
            ->firstOrFail();
        
        $reservation->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Book request cancelled successfully.');
    }
}
