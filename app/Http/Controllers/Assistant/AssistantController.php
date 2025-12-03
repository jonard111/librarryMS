<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function dashboard()
    {
        // Total Books
        $totalBooks = \App\Models\Book::count();
        
        // Active Reservations (pending + approved)
        $activeReservations = \App\Models\BookReservation::whereIn('status', ['pending', 'approved'])->count();
        
        // Books Currently Borrowed
        $booksBorrowed = \App\Models\BookReservation::where('status', 'picked_up')
            ->whereNull('return_date')
            ->count();
        
        // Total E-Books
        $totalEbooks = \App\Models\Ebook::count();
        
        return view('assistant.assistant_dashboard', compact(
            'totalBooks',
            'activeReservations',
            'booksBorrowed',
            'totalEbooks'
        ));
    }

    public function allBooks()
    {
        $categoryOptions = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];
        
        $booksByCategory = Book::latest()->get()->groupBy('category');

        return view('assistant.all_book', [
            'categories' => $categoryOptions,
            'booksByCategory' => $booksByCategory,
        ]);
    }

    public function allEbooks()
    {
        $categoryOptions = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];
        
        $ebooksByCategory = Ebook::latest()->get()->groupBy('category');

        return view('assistant.all_ebooks', [
            'categories' => $categoryOptions,
            'ebooksByCategory' => $ebooksByCategory,
        ]);
    }


    public function manageBooks()
    {
        $popularBooks = Book::latest()->take(6)->get();
        $popularEbooks = Ebook::latest()->take(6)->get();
        
        return view('assistant.manage_books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    }

    public function notification()
    {
        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole('assistant')
            ->latest('publish_at')
            ->take(10)
            ->get();

        return view('assistant.notification', ['notifications' => $announcements]);
    }

    public function reservation()
    {
        $reservations = BookReservation::with(['user', 'book'])
            ->orderBy('reservation_date', 'desc')
            ->get();

        $activeBorrowers = BookReservation::with(['user', 'book'])
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->orderBy('pickup_date', 'desc')
            ->get();

        $activeReservations = BookReservation::whereIn('status', ['pending', 'approved'])->count();
        $booksBorrowed = BookReservation::where('status', 'picked_up')->whereNull('return_date')->count();
        $booksReturned = BookReservation::where('status', 'returned')->count();
        $overdueBooks = BookReservation::where('status', 'picked_up')
            ->whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();

        return view('assistant.reservation', [
            'reservations' => $reservations,
            'activeBorrowers' => $activeBorrowers,
            'activeReservations' => $activeReservations,
            'booksBorrowed' => $booksBorrowed,
            'booksReturned' => $booksReturned,
            'overdueBooks' => $overdueBooks,
        ]);
    }

    public function approveRequest(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }
        
        $reservation->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Request approved successfully.');
    }

    public function approveReservation(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as picked up.');
        }
        
        $reservation->update([
            'status' => 'picked_up',
            'pickup_date' => now(),
            'due_date' => now()->addDays(7), // 7 days loan period
        ]);

        return back()->with('success', 'Book marked as picked up successfully.');
    }

    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        return back()->with('success', 'Book returned successfully.');
    }

    public function destroyReservation($id)
    {
        $reservation = BookReservation::findOrFail($id);
        $reservation->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }

    public function announcement()
    {
        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole('assistant')
            ->latest('publish_at')
            ->get();

        return view('assistant.announcement', compact('announcements'));
    }

    public function student()
    {
        // Get all students
        $students = \App\Models\User::where('role', 'student')->get();
        
        // Calculate statistics
        $totalStudents = $students->count();
        $activeStudents = $students->where('account_status', 'approved')->count();
        $inactiveStudents = $students->whereIn('account_status', ['pending', 'rejected'])->count();
        
        // New students this month
        $newStudents = $students->filter(function ($student) {
            return \Carbon\Carbon::parse($student->registration_date)->isCurrentMonth();
        })->count();
        
        // Get student borrowing records with relationships
        $borrowingRecords = \App\Models\BookReservation::with(['user', 'book'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->whereIn('status', ['picked_up', 'approved'])
            ->get()
            ->groupBy('user_id')
            ->map(function ($reservations, $userId) {
                $user = $reservations->first()->user;
                $borrowedCount = $reservations->where('status', 'picked_up')->count();
                
                // Calculate overdue books
                $overdueCount = $reservations->filter(function ($reservation) {
                    if ($reservation->status === 'picked_up' && $reservation->due_date) {
                        return \Carbon\Carbon::parse($reservation->due_date)->isPast() && !$reservation->return_date;
                    }
                    return false;
                })->count();
                
                // Determine payment status (for now, based on overdue)
                $hasOverdue = $overdueCount > 0;
                
                return [
                    'user' => $user,
                    'borrowed_count' => $borrowedCount,
                    'overdue_count' => $overdueCount,
                    'has_overdue' => $hasOverdue,
                    'reservations' => $reservations,
                ];
            })
            ->values();
        
        // Calculate percentage changes (simplified - comparing to last month)
        $lastMonthTotal = \App\Models\User::where('role', 'student')
            ->where('registration_date', '<', \Carbon\Carbon::now()->startOfMonth())
            ->count();
        $totalChange = $lastMonthTotal > 0 ? round((($totalStudents - $lastMonthTotal) / $lastMonthTotal) * 100) : 0;
        
        $lastMonthActive = \App\Models\User::where('role', 'student')
            ->where('account_status', 'approved')
            ->where('registration_date', '<', \Carbon\Carbon::now()->startOfMonth())
            ->count();
        $activeChange = $lastMonthActive > 0 ? round((($activeStudents - $lastMonthActive) / $lastMonthActive) * 100) : 0;
        
        $lastMonthInactive = \App\Models\User::where('role', 'student')
            ->whereIn('account_status', ['pending', 'rejected'])
            ->where('registration_date', '<', \Carbon\Carbon::now()->startOfMonth())
            ->count();
        $inactiveChange = $lastMonthInactive > 0 ? round((($inactiveStudents - $lastMonthInactive) / $lastMonthInactive) * 100) : 0;
        
        $lastMonthNew = \App\Models\User::where('role', 'student')
            ->whereBetween('registration_date', [
                \Carbon\Carbon::now()->subMonth()->startOfMonth(),
                \Carbon\Carbon::now()->subMonth()->endOfMonth()
            ])
            ->count();
        $newChange = $lastMonthNew > 0 ? round((($newStudents - $lastMonthNew) / $lastMonthNew) * 100) : 0;
        
        return view('assistant.student', [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'inactiveStudents' => $inactiveStudents,
            'newStudents' => $newStudents,
            'totalChange' => $totalChange,
            'activeChange' => $activeChange,
            'inactiveChange' => $inactiveChange,
            'newChange' => $newChange,
            'borrowingRecords' => $borrowingRecords,
        ]);
    }

    public function users()
    {
        return view('assistant.users');
    }
}
