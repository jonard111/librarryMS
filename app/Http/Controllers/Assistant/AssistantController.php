<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\BookReservation;
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
        
        $pickupAt = now();
        $loanDuration = $reservation->loan_duration ?? 7;
        $loanUnit = $reservation->loan_duration_unit ?? 'day';

        $dueDate = $loanUnit === 'hour'
            ? $pickupAt->copy()->addHours($loanDuration)
            : $pickupAt->copy()->addDays($loanDuration);

        $reservation->update([
            'status' => 'picked_up',
            'pickup_date' => $pickupAt,
            'due_date' => $dueDate,
            'fine_amount' => 0,
            'fine_paid_at' => null,
        ]);

        return back()->with('success', 'Book marked as picked up successfully.');
    }

    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);

        if ($reservation->has_unsettled_fine) {
            $fineAmount = number_format($reservation->current_fine, 2);

            return back()->with('error', "Payment required before returning this book. Outstanding fine: ₱{$fineAmount}.");
        }

        $fineAmount = $reservation->fine_amount ?: $reservation->current_fine;

        $reservation->update([
            'status' => 'returned',
            'return_date' => now(),
            'fine_amount' => $fineAmount,
        ]);

        return back()->with('success', 'Book returned successfully.');
    }

    public function settleFine($id)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($id);

        if (!$reservation->has_unsettled_fine) {
            return back()->with('error', 'This reservation does not have any outstanding fines.');
        }

        $fineAmount = $reservation->current_fine;

        if ($fineAmount <= 0) {
            return back()->with('error', 'Unable to calculate an outstanding fine for this reservation.');
        }

        $reservation->update([
            'fine_amount' => $fineAmount,
            'fine_paid_at' => now(),
        ]);

        $studentName = $reservation->user?->full_name ?? 'The borrower';

        return back()->with('success', "{$studentName}'s overdue fine of ₱" . number_format($fineAmount, 2) . ' has been recorded as paid.');
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
        $borrowingRecords = BookReservation::with(['user', 'book'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->whereIn('status', ['picked_up', 'approved'])
            ->get()
            ->groupBy('user_id')
            ->map(function ($reservations) {
                $user = $reservations->first()->user;
                $activeLoans = $reservations->where('status', 'picked_up');
                $overdueReservations = $activeLoans->filter->is_overdue;
                $unsettledReservations = $overdueReservations->filter->has_unsettled_fine;
                $totalFine = $unsettledReservations->sum(function ($reservation) {
                    return $reservation->current_fine;
                });

                $latestReservation = $reservations->sortByDesc(function ($reservation) {
                    return $reservation->reservation_date ?? $reservation->created_at;
                })->first();

                return [
                    'user' => $user,
                    'borrowed_count' => $activeLoans->count(),
                    'overdue_count' => $overdueReservations->count(),
                    'requires_payment' => $unsettledReservations->isNotEmpty(),
                    'unsettled_count' => $unsettledReservations->count(),
                    'fine_due' => round($totalFine, 2),
                    'reservations' => $reservations,
                    'unsettled_reservations' => $unsettledReservations->values(),
                    'latest_loan_label' => $latestReservation ? $latestReservation->loan_duration_label : null,
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

}
