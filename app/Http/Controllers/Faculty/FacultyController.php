<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Borrowed Books (currently picked up)
        $borrowedBooksCount = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->count();
        
        // Pending Requests
        $pendingRequests = BookReservation::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        
        // Active Announcements for faculty role
        $announcementsCount = Announcement::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->visibleForRole('faculty')
            ->count();
        
        // Total E-Books
        $totalEbooks = Ebook::count();
        
        return view('faculty.faculty_dashboard', compact(
            'borrowedBooksCount',
            'pendingRequests',
            'announcementsCount',
            'totalEbooks'
        ));
    }

    public function borrowedBooks()
    {
        return view('faculty.borrowed_books');
    }

    public function requestBooks()
    {
        return view('faculty.request_books');
    }

    public function announcement()
    {
        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole('faculty')
            ->latest('publish_at')
            ->get();

        return view('faculty.announcement', compact('announcements'));
    }

    public function notification()
    {
        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole('faculty')
            ->latest('publish_at')
            ->take(10)
            ->get();

        return view('faculty.notification', ['notifications' => $announcements]);
    }

    public function books()
    {
        $popularBooks = Book::latest()->take(6)->get();
        $popularEbooks = Ebook::latest()->take(6)->get();
        
        return view('faculty.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    }

    public function showBook($id)
    {
        $book = Book::findOrFail($id);
        $hasReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->exists();
        
        return view('faculty.book_details', compact('book', 'hasReservation'));
    }

    public function showEbook($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->increment('views');
        
        // If it's an AJAX request (from modal), return JSON
        if (request()->ajax()) {
            return response()->json([
                'views' => $ebook->fresh()->views
            ]);
        }
        
        return view('faculty.ebook_details', compact('ebook'));
    }

    public function reserveBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $existingReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->first();
        
        if ($existingReservation) {
            return back()->with('error', 'You already have an active reservation for this book.');
        }
        
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
}
