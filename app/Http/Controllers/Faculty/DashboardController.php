<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\Announcement;
use App\Models\Ebook;
use App\Models\ReadingList;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * DashboardController
 * 
 * Handles faculty dashboard data aggregation including:
 * - Statistics (borrowed books, pending requests, announcements, ebooks)
 * - Alert notifications (overdue, due soon, ready for pickup)
 * - Reading statistics
 * - Recent announcements
 * - Recommended books
 * - Reading list preview
 */
class DashboardController extends Controller
{
    /**
     * Display faculty dashboard with comprehensive data
     * 
     * Collects and organizes:
     * - Statistics cards
     * - Alert notifications (overdue, due soon, ready for pickup)
     * - Quick stats (total read, reading list count, overdue count)
     * - Recent announcements
     * - Recommended books
     * - Reading list preview
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $userId = Auth::id();
        
        // STATISTICS CARDS: Basic counts for dashboard cards
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
        
        // READING LIST: Get user's saved books (latest 6 for dashboard preview)
        $readingList = ReadingList::where('user_id', $userId)
            ->with('book')
            ->latest('created_at')
            ->take(6)
            ->get();
        
        // ALERT: Books due soon (within next 3 days) - shows warning alert
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
        
        // ALERT: Overdue books - shows danger alert with fine information
        // Overdue books (past due date, not returned)
        $overdueBooks = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNull('return_date')
            ->with('book')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // RECENT ANNOUNCEMENTS: Latest 3 announcements for preview section
        // Recent announcements (latest 3)
        $recentAnnouncements = Announcement::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->visibleForRole('faculty')
            ->latest('created_at')
            ->take(3)
            ->get();
        
        // RECOMMENDED BOOKS: Personalized recommendations based on reading list categories
        // Step 1: Get categories from user's reading list
        $readingListCategories = ReadingList::where('user_id', $userId)
            ->with('book')
            ->get()
            ->pluck('book.category')
            ->filter()
            ->unique()
            ->toArray();
        
        // Step 2: Find books in those categories that user hasn't reserved
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
        
        // Step 3: Fallback to popular books if no category-based recommendations
        if ($recommendedBooks->isEmpty()) {
            $recommendedBooks = Book::latest()->take(6)->get();
        }
        
        // QUICK STATS: Reading statistics for dashboard cards
        // Reading statistics
        $totalBooksRead = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();
        
        $currentMonthRead = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->whereMonth('return_date', now()->month)
            ->whereYear('return_date', now()->year)
            ->count();
        
        // ALERT: Books ready for pickup - shows success alert
        // Approved books ready for pickup (status: approved, waiting for physical pickup)
        $readyForPickup = BookReservation::where('user_id', $userId)
            ->where('status', 'approved')
            ->with('book')
            ->latest('pickup_date')
            ->take(3)
            ->get();
        
        return view('faculty.faculty_dashboard', compact(
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

