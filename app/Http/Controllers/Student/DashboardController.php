<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\Announcement;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;

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
        
        // Reading list (recently viewed or borrowed books)
        $readingList = BookReservation::where('user_id', $userId)
            ->with('book')
            ->whereIn('status', ['picked_up', 'approved'])
            ->latest('reservation_date')
            ->take(6)
            ->get();
        
        return view('student.student_dashboard', compact(
            'borrowedBooksCount',
            'pendingRequests',
            'announcementsCount',
            'totalEbooks',
            'readingList'
        ));
    }
}

