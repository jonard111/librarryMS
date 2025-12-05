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

        // Get active borrowers - exclude books that are settled/returned
        // Settled books (fine_paid_at is not null) are already returned, so exclude them
        $activeBorrowers = BookReservation::with(['user', 'book'])
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->whereNull('fine_paid_at') // Exclude settled books (they're already returned)
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

    /**
     * Mark an approved reservation as "picked up"
     * 
     * Process:
     * 1. Verify reservation status is 'approved'
     * 2. Calculate due date based on loan duration (days or hours)
     * 3. Set pickup_date to current time
     * 4. Update status to 'picked_up'
     * 5. Reset fine_amount and fine_paid_at
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveReservation(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        // Validate: Only approved reservations can be marked as picked up
        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as picked up.');
        }
        
        // Calculate due date based on user-selected loan duration
        $pickupAt = now();
        $loanDuration = $reservation->loan_duration ?? 7; // Default: 7 days
        $loanUnit = $reservation->loan_duration_unit ?? 'day'; // Default: days

        // Calculate due date: add hours or days based on loan_unit
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

    /**
     * Process book return
     * 
     * Process:
     * 1. Check if there's an unsettled fine (block return if yes)
     * 2. Calculate fine amount if overdue
     * 3. Update status to 'returned'
     * 4. Set return_date to current time
     * 5. Record fine_amount
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);

        // STEP 1: Block return if there's an unsettled fine
        if ($reservation->has_unsettled_fine) {
            $fineAmount = number_format($reservation->current_fine, 2);

            return back()->with('error', "Payment required before returning this book. Outstanding fine: ₱{$fineAmount}.");
        }

        // STEP 2: Calculate fine amount (use existing or calculate current)
        $fineAmount = $reservation->fine_amount ?: $reservation->current_fine;

        $reservation->update([
            'status' => 'returned',
            'return_date' => now(),
            'fine_amount' => $fineAmount,
        ]);

        return back()->with('success', 'Book returned successfully.');
    }

    /**
     * Settle (mark as paid) an overdue fine
     * 
     * Process:
     * 1. Verify reservation has unsettled fine
     * 2. Calculate current fine amount
     * 3. Update fine_amount and set fine_paid_at timestamp
     * 
     * @param int $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function settleFine($id)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($id);

        // STEP 1: Verify there's an unsettled fine
        if (!$reservation->has_unsettled_fine) {
            return back()->with('error', 'This reservation does not have any outstanding fines.');
        }

        // STEP 2: Calculate current fine amount
        // - Hourly loans: ₱1.00 per hour overdue
        // - Daily loans: ₱10.00 per day overdue
        $fineAmount = $reservation->calculateFine();

        // If calculated fine is 0 or less, calculate manually as fallback
        if ($fineAmount <= 0 && $reservation->due_date) {
            $loanUnit = $reservation->loan_duration_unit ?? 'day';
            $dueDate = \Carbon\Carbon::parse($reservation->due_date);
            $now = \Carbon\Carbon::now();
            
            if ($loanUnit === 'hour') {
                // Calculate hours overdue for hourly loans
                if ($dueDate->isPast()) {
                    $overdueHours = max(1, $now->diffInHours($dueDate));
                    $fineAmount = round($overdueHours * 1.00, 2);
                }
            } else {
                // Calculate days overdue for daily loans
                $dueDateStart = $dueDate->copy()->startOfDay();
                $nowStart = $now->copy()->startOfDay();
                
                if ($dueDateStart->isPast()) {
                    $overdueDays = max(1, $nowStart->diffInDays($dueDateStart));
                    $fineAmount = round($overdueDays * 10.00, 2);
                }
            }
        }

        // Final check - ensure we have a valid fine amount
        if ($fineAmount <= 0) {
            \Log::error('Fine calculation failed', [
                'reservation_id' => $reservation->id,
                'due_date' => $reservation->due_date,
                'status' => $reservation->status,
                'return_date' => $reservation->return_date,
                'is_overdue' => $reservation->isOverdue(),
                'calculated_fine' => $fineAmount,
            ]);
            return back()->with('error', 'Unable to calculate an outstanding fine for this reservation. Please check the due date and status.');
        }

        // STEP 4: Mark fine as paid AND automatically return the book
        $reservation->update([
            'fine_amount' => $fineAmount,
            'fine_paid_at' => now(),
            'status' => 'returned',
            'return_date' => now(),
        ]);

        $studentName = $reservation->user?->full_name ?? 'The borrower';
        $bookTitle = $reservation->book?->title ?? 'the book';

        return back()->with('success', "{$studentName}'s overdue fine of ₱" . number_format($fineAmount, 2) . " has been paid and '{$bookTitle}' has been marked as returned.");
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
        
        // ADDITIONAL STATISTICS: Books and Fines
        // Books currently borrowed by all students
        $booksCurrentlyBorrowed = BookReservation::whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->count();
        
        // Overdue books count
        $overdueBooksCount = BookReservation::whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();
        
        // Total fines collected this month (fines that were paid this month)
        $totalFinesCollected = BookReservation::whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->whereNotNull('fine_paid_at')
            ->whereMonth('fine_paid_at', now()->month)
            ->whereYear('fine_paid_at', now()->year)
            ->sum('fine_amount');
        
        // Pending fines (unpaid fines for overdue books)
        $pendingFines = BookReservation::whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->where('due_date', '<', now())
            ->whereNull('fine_paid_at')
            ->get()
            ->sum(function ($reservation) {
                return $reservation->current_fine;
            });
        
        // Get individual book reservations (not grouped by user)
        // Each book borrowed by a student will be a separate row
        // Only get books that are actually picked up (not just approved)
        // Exclude settled books (fine_paid_at is not null) - they're already returned
        $borrowingRecords = BookReservation::with(['user', 'book'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'picked_up') // Only books that are actually borrowed
            ->whereNull('return_date') // Only books that haven't been returned yet
            ->whereNull('fine_paid_at') // Exclude settled books (they're already returned)
            ->orderBy('due_date', 'asc') // Order by due date (overdue first)
            ->get()
            ->map(function ($reservation) {
                // Calculate fine information for this specific reservation
                $isOverdue = $reservation->isOverdue();
                $hasUnsettledFine = $reservation->hasUnsettledFine();
                $fineAmount = $hasUnsettledFine ? $reservation->current_fine : 0;
                
                return [
                    'reservation' => $reservation,
                    'user' => $reservation->user,
                    'book' => $reservation->book,
                    'is_overdue' => $isOverdue,
                    'has_unsettled_fine' => $hasUnsettledFine,
                    'fine_due' => round($fineAmount, 2),
                    'requires_payment' => $hasUnsettledFine,
                    'due_date' => $reservation->due_date,
                    'pickup_date' => $reservation->pickup_date,
                    'loan_duration_label' => $reservation->loan_duration_label,
                ];
            });
        
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
        
        // Get returned books with fine information
        // Shows books that have been returned, including those that had fines
        $returnedBooks = BookReservation::with(['user', 'book'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'student');
            })
            ->where('status', 'returned') // Only returned books
            ->whereNotNull('return_date') // Must have return date
            ->orderBy('return_date', 'desc') // Most recently returned first
            ->get()
            ->map(function ($reservation) {
                // Check if this returned book had fines
                $hadFine = $reservation->fine_amount > 0 || $reservation->fine_paid_at !== null;
                $finePaid = $reservation->fine_paid_at !== null;
                
                return [
                    'reservation' => $reservation,
                    'user' => $reservation->user,
                    'book' => $reservation->book,
                    'had_fine' => $hadFine,
                    'fine_paid' => $finePaid,
                    'fine_amount' => round($reservation->fine_amount ?? 0, 2),
                    'fine_paid_at' => $reservation->fine_paid_at,
                    'return_date' => $reservation->return_date,
                    'due_date' => $reservation->due_date,
                    'pickup_date' => $reservation->pickup_date,
                    'loan_duration_label' => $reservation->loan_duration_label,
                    'was_overdue' => $reservation->due_date && $reservation->return_date && 
                                     \Carbon\Carbon::parse($reservation->return_date)->gt(\Carbon\Carbon::parse($reservation->due_date)),
                ];
            });
        
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
            'returnedBooks' => $returnedBooks, // New: returned books with fine info
            // Additional statistics
            'booksCurrentlyBorrowed' => $booksCurrentlyBorrowed,
            'overdueBooksCount' => $overdueBooksCount,
            'totalFinesCollected' => round($totalFinesCollected, 2),
            'pendingFines' => round($pendingFines, 2),
        ]);
    }

}
