<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\Announcement;
use App\Models\Ebook;
use App\Models\ReadingList;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
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
        
        // Active Announcements for student role
        $announcementsCount = Announcement::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->visibleForRole('student')
            ->count();
        
        // Total E-Books
        $totalEbooks = Ebook::count();
        
        // Reading list (books saved by user)
        $readingList = ReadingList::where('user_id', $userId)
            ->with('book')
            ->latest('created_at')
            ->take(6)
            ->get();
        
        // Books due soon (within 3 days)
        $booksDueSoon = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->with('book')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
        
        // Overdue books
        $overdueBooks = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->with('book')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Recent announcements (latest 3)
        $recentAnnouncements = Announcement::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->visibleForRole('student')
            ->latest('created_at')
            ->take(3)
            ->get();
        
        // Recommended books (based on reading list categories or popular books)
        $readingListCategories = ReadingList::where('user_id', $userId)
            ->with('book')
            ->get()
            ->pluck('book.category')
            ->filter()
            ->unique()
            ->toArray();
        
        $recommendedBooks = Book::whereIn('category', $readingListCategories)
            ->orWhere(function($query) {
                $query->whereNotIn('id', function($subquery) {
                    $subquery->select('book_id')
                        ->from('book_reservations')
                        ->where('user_id', Auth::id())
                        ->whereIn('status', ['pending', 'approved', 'picked_up']);
                });
            })
            ->latest()
            ->take(6)
            ->get();
        
        // If no recommendations based on reading list, show popular books
        if ($recommendedBooks->isEmpty()) {
            $recommendedBooks = Book::latest()->take(6)->get();
        }
        
        // Reading statistics
        $totalBooksRead = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();
        
        $currentMonthRead = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->whereMonth('return_date', now()->month)
            ->whereYear('return_date', now()->year)
            ->count();
        
        // Approved books ready for pickup
        $readyForPickup = BookReservation::where('user_id', $userId)
            ->where('status', 'approved')
            ->with('book')
            ->latest('pickup_date')
            ->take(3)
            ->get();
        
        return view('student.student_dashboard', compact(
            'borrowedBooksCount',
            'pendingRequests',
            'announcementsCount',
            'totalEbooks',
            'readingList',
            'booksDueSoon',
            'overdueBooks',
            'recentAnnouncements',
            'recommendedBooks',
            'totalBooksRead',
            'currentMonthRead',
            'readyForPickup'
        ));
    }
}

