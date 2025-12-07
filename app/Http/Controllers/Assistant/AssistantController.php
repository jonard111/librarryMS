<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\BookReservation;
use App\Models\Ebook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Dashboard – Summary Statistics and Recent Transactions (Audit Trail Replacement)
     * * Generates recent activities dynamically by combining events from BookReservation records.
     * * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $now = Carbon::now();
        
        // --- 1. Fetch Core Statistics ---
        
        $totalBooks         = Book::count();
        $activeReservations = BookReservation::whereIn('status', ['pending', 'approved'])->count();
        $booksBorrowed      = BookReservation::where('status', 'picked_up')->whereNull('return_date')->count();
        $totalEbooks        = Ebook::count();

        // --- 2. Generate Recent Transactions from BookReservations ---

        $recentTransactions = BookReservation::with(['user', 'book'])
            ->orderBy('updated_at', 'desc')
            ->take(15) // Fetch a batch to process multiple recent status changes
            ->get()
            ->flatMap(function ($reservation) {
                $activities = collect();
                $userFullName = optional($reservation->user)->full_name ?? 'N/A User';
                $bookTitle = optional($reservation->book)->title ?? 'N/A Book';
                
                // Event 1: Reservation Requested (Pending)
                $activities->push((object)[
                    'created_at' => $reservation->created_at,
                    'activity_type' => 'reservation_pending',
                    'details' => "Request received for '{$bookTitle}' by {$userFullName}.",
                ]);

                // Event 2: Reservation Approved (Based on first update after creation, assuming update means approval)
                if ($reservation->status !== 'pending' && $reservation->updated_at > $reservation->created_at) {
                    $activities->push((object)[
                        'created_at' => $reservation->updated_at,
                        'activity_type' => 'reservation_approved',
                        'details' => "Reservation approved for '{$bookTitle}' for {$userFullName}.",
                    ]);
                }
                
                // Event 3: Book Picked Up (Borrowed)
                if ($reservation->pickup_date) {
                    $activities->push((object)[
                        'created_at' => $reservation->pickup_date,
                        'activity_type' => 'book_borrowed',
                        'details' => "Book '{$bookTitle}' picked up by {$userFullName} (Due: {$reservation->due_date->format('M d, Y')}).",
                    ]);
                }

                // Event 4: Book Returned
                if ($reservation->return_date) {
                    $activities->push((object)[
                        'created_at' => $reservation->return_date,
                        'activity_type' => 'book_returned',
                        'details' => "Book '{$bookTitle}' returned by {$userFullName}.",
                    ]);
                }
                
                // Event 5: Fine Settled
                if ($reservation->fine_paid_at) {
                    $fine = $reservation->fine_amount ?? 0;
                    $activities->push((object)[
                        'created_at' => $reservation->fine_paid_at,
                        'activity_type' => 'fine_settled',
                        'details' => "Fine of ₱" . number_format($fine, 2) . " settled by {$userFullName}.",
                    ]);
                }
                
                return $activities;
            })
            ->sortByDesc('created_at') 
            ->unique(fn($item) => $item->created_at . $item->activity_type)
            ->take(10); // Display top 10 recent activities

        // --- 3. Return View ---
        
        return view('assistant.assistant_dashboard', [
            'totalBooks'         => $totalBooks,
            'activeReservations' => $activeReservations,
            'booksBorrowed'      => $booksBorrowed,
            'totalEbooks'        => $totalEbooks,
            'recentActivities'   => $recentTransactions,
        ]);
    }


    /**
     * All Physical Books (Grouped by Category)
     * * @return \Illuminate\View\View
     */
    public function allBooks()
    {
        $categoryOptions = [
            'education' => 'Education & Learning',
            'science'   => 'Science & Technology',
            'literature'=> 'Literature / Fiction',
            'history'   => 'History',
            'selfhelp'  => 'Self-Help / Motivation',
        ];

        return view('assistant.all_book', [
            'categories'       => $categoryOptions,
            'booksByCategory'  => Book::latest()->get()->groupBy('category'),
        ]);
    }

    /**
     * All E-Books (Grouped by Category)
     * * @return \Illuminate\View\View
     */
    public function allEbooks()
    {
        $categoryOptions = [
            'education' => 'Education & Learning',
            'science'   => 'Science & Technology',
            'literature'=> 'Literature / Fiction',
            'history'   => 'History',
            'selfhelp'  => 'Self-Help / Motivation',
        ];

        return view('assistant.all_ebooks', [
            'categories'      => $categoryOptions,
            'ebooksByCategory'=> Ebook::latest()->get()->groupBy('category'),
        ]);
    }

    /**
     * Display Manage Books Page (Popular Items)
     * * Fetches the 4 most recent books and 4 most recent ebooks for the dashboard summary view.
     * * @return \Illuminate\View\View
     */
    public function manageBooks()
    {
        // FIX: Limit the popular books and ebooks to 4 items each
        return view('assistant.manage_books', [
            'popularBooks'  => Book::latest()->take(4)->get(),
            'popularEbooks' => Ebook::latest()->take(4)->get(),
        ]);
    }

    /**
     * Assistant Notifications
     * * Fetches system announcements and active reservation requests for attention.
     * * @return \Illuminate\View\View
     */
public function notification()
{
    // 1. Fetch System Announcements
    $announcements = Announcement::published()
        ->with('creator')
        ->visibleForRole('assistant')
        ->latest('publish_at')
        ->take(10)
        ->get();

    // 2. Fetch Pending Reservations (Needs Assistant action)
$pendingReservations = BookReservation::with(['user', 'book'])
    ->where('status', 'pending')
    ->latest('created_at')
    // EXECUTE THE QUERY FIRST using ->get()
    ->get()
    // NOW you can use ->map() on the resulting Collection
    ->map(fn($res) => (object) [
        'id' => 'res_pending_' . $res->id, // Unique ID for custom object
        'title' => 'New Reservation Request: ' . optional($res->book)->title,
        'body' => "A new reservation request has been submitted by " . optional($res->user)->full_name . " for '" . optional($res->book)->title . "'. Please review and approve/reject.",
        'type' => 'reservation_pending',
        'created_at' => $res->created_at,
        'creator' => null, 
    ]);
        
    // 3. Fetch Overdue Alerts (Optional, but useful for Assistant dashboard)
    // You should add similar logic here if you want overdue alerts to show up in the notification stream.
    
    // 4. Combine and Sort notifications by timestamp
    $notifications = $announcements
        ->concat($pendingReservations)
        ->sortByDesc('created_at');

    return view('assistant.notification', compact('notifications'));
}

    /**
     * Reservation Records Page
     * * @return \Illuminate\View\View
     */
    public function reservation()
    {
        $reservations = BookReservation::with(['user', 'book'])
            ->orderBy('reservation_date', 'desc')
            ->get();

        $activeBorrowers = BookReservation::with(['user', 'book'])
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            // Fine payment status is checked in the view/accessor logic
            ->orderBy('pickup_date', 'desc')
            ->get();

        return view('assistant.reservation', [
            'reservations'        => $reservations,
            'activeBorrowers'     => $activeBorrowers,
            'activeReservations'  => BookReservation::whereIn('status', ['pending', 'approved'])->count(),
            'booksBorrowed'       => BookReservation::where('status', 'picked_up')->whereNull('return_date')->count(),
            'booksReturned'       => BookReservation::where('status', 'returned')->count(),
            'overdueBooks'        => BookReservation::where('status', 'picked_up')
                                        ->whereNull('return_date')
                                        ->where('due_date', '<', now())
                                        ->count(),
        ]);
        
    }

    /**
     * Approve a Reservation Request (Status: Pending → Approved)
     * * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveRequest(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        $reservation->update(['status' => 'approved']);

        return back()->with('success', 'Request approved successfully.');
    }

    /**
     * Mark Reservation as Picked Up (Status: Approved → Picked Up)
     * * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveReservation(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);

        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as picked up.');
        }

        $pickup = now();
        $duration = $reservation->loan_duration ?? 7;
        $unit = $reservation->loan_duration_unit ?? 'day';

        $dueDate = $unit === 'hour'
            ? $pickup->copy()->addHours($duration)
            : $pickup->copy()->addDays($duration);

        $reservation->update([
            'status'      => 'picked_up',
            'pickup_date' => $pickup,
            'due_date'    => $dueDate,
            'fine_amount' => 0,
            'fine_paid_at'=> null,
        ]);

        return back()->with('success', 'Book marked as picked up successfully.');
    }

    /**
     * Process Book Return
     * * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);

        // Calculate current fine before checking if it needs settlement
        $currentFine = $reservation->calculateFine(); 

        if ($currentFine > 0 && !$reservation->fine_paid_at) {
            return back()->with(
                'error',
                "Payment required before returning this book. Outstanding fine: ₱" . number_format($currentFine, 2)
            );
        }

        // Use the calculated fine (if fine_amount wasn't set earlier)
        $fineAmount = $reservation->fine_amount ?: $currentFine; 

        $reservation->update([
            'status'      => 'returned',
            'return_date' => now(),
            'fine_amount' => $fineAmount,
            // If fine was previously paid via settleFine, fine_paid_at remains set.
            // If no fine was due, fine_paid_at remains null.
        ]);

        return back()->with('success', 'Book returned successfully.');
    }

    /**
     * Settle Fines
     * * Marks fine as paid and updates reservation status to 'returned'.
     * * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function settleFine($id)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($id);
        $fineAmount = $reservation->calculateFine();

        if ($fineAmount <= 0) {
            return back()->with('error', 'No outstanding fine is currently due for this reservation.');
        }
        
        // Ensure fine hasn't been paid already (optional check, but good practice)
        if ($reservation->fine_paid_at) {
             return back()->with('error', 'The fine has already been settled.');
        }

        $reservation->update([
            'status'       => 'returned',
            'return_date'  => now(),
            'fine_amount'  => $fineAmount, // Record the exact amount paid/calculated at settlement
            'fine_paid_at' => now(),
        ]);

        return back()->with(
            'success',
            "{$reservation->user->full_name}'s fine of ₱" . number_format($fineAmount, 2) .
            " has been paid and '{$reservation->book->title}' has been marked as returned."
        );
    }

    /**
     * Delete Reservation Permanently
     * * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyReservation($id)
    {
        BookReservation::findOrFail($id)->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }

    /**
     * View Announcements
     * * @return \Illuminate\View\View
     */
    public function announcement()
    {
        return view('assistant.announcement', [
            'announcements' => Announcement::published()
                ->with('creator')
                ->visibleForRole('assistant')
                ->latest('publish_at')
                ->get(),
        ]);
    }

    /**
     * Student Management & Statistics
     * * Calculates student count changes vs. the previous month.
     * * @return \Illuminate\View\View
     */
    public function student()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // --- 1. Current Month/Total Counts ---
        $students = User::where('role', 'student')->get();
        $totalStudents = $students->count();
        $activeStudents = $students->where('account_status', 'approved')->count();
        $inactiveStudents = $students->whereIn('account_status', ['pending', 'rejected'])->count();
        
        // New students: Registered during the CURRENT calendar month
        $newStudents = User::where('role', 'student')
            ->whereBetween('registration_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();
        
        // --- 2. Last Month's Baseline Counts (for Comparison) ---
        
        // Total students LAST MONTH: Students registered BEFORE the start of the CURRENT month.
        $studentsBeforeThisMonth = User::where('role', 'student')
            ->where('registration_date', '<', $now->copy()->startOfMonth())
            ->get(); 
        
        $totalLastMonth = $studentsBeforeThisMonth->count();
        $activeLastMonth = $studentsBeforeThisMonth->where('account_status', 'approved')->count();
        $inactiveLastMonth = $studentsBeforeThisMonth->whereIn('account_status', ['pending', 'rejected'])->count();
        
        // New students LAST MONTH: Registered during the PREVIOUS calendar month
        $newLastMonth = User::where('role', 'student')
            ->whereBetween('registration_date', [$lastMonth->copy()->startOfMonth(), $lastMonth->copy()->endOfMonth()])
            ->count();
        
        // --- 3. Percentage Change Calculation Function ---

        $calculateChange = function ($current, $previous) {
            if ($previous === 0) {
                // Return 100% for readability if there was growth from zero baseline
                return $current > 0 ? 100 : 0; 
            }
            // Standard formula: ((Current - Previous) / Previous) * 100
            return round((($current - $previous) / $previous) * 100, 1);
        };

        // --- 4. Apply Calculation to all stats ---
        
        $totalChange    = $calculateChange($totalStudents, $totalLastMonth);
        $activeChange   = $calculateChange($activeStudents, $activeLastMonth);
        $inactiveChange = $calculateChange($inactiveStudents, $inactiveLastMonth);
        $newChange      = $calculateChange($newStudents, $newLastMonth);


        // --- 5. Borrowing Records (Only currently overdue records for tables) ---
        
        $borrowingRecords = BookReservation::with(['user', 'book'])
            ->whereHas('user', fn($q) => $q->where('role', 'student'))
            ->where('status', 'picked_up')
            ->whereNull('return_date')
            ->where('due_date', '<', $now) // Filter for currently overdue
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($r) {
                $fineDue = $r->isOverdue() ? $r->calculateFine() : 0.0;
                
                return [
                    'reservation' => $r,
                    'user'        => $r->user,
                    'book'        => $r->book,
                    'is_overdue'  => $r->isOverdue(),
                    'has_unsettled_fine' => $r->hasUnsettledFine(),
                    'fine_due'    => round($fineDue, 2),
                    'requires_payment' => $r->hasUnsettledFine(),
                    'due_date'    => $r->due_date,
                    'pickup_date' => $r->pickup_date,
                    'loan_duration_label' => $r->loan_duration_label,
                ];
            });

        // --- 6. Returned Books History ---
        
        $returnedBooks = BookReservation::with(['user', 'book'])
            ->whereHas('user', fn($q) => $q->where('role', 'student'))
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc')
            ->get()
            ->map(function ($r) {
                $hadFine = $r->fine_amount > 0;
                $finePaid = $r->fine_paid_at !== null;

                return [
                    'user'        => $r->user,
                    'book'        => $r->book,
                    'pickup_date' => $r->pickup_date,
                    'return_date' => $r->return_date,
                    'fine_amount' => $r->fine_amount,
                    'fine_paid_at'=> $r->fine_paid_at,
                    'had_fine'    => $hadFine,
                    'fine_paid'   => $finePaid,
                ];
            });

        // --- 7. Return View Data ---

        return view('assistant.student', [
            // Student Counts
            'totalStudents' => $totalStudents,
            'activeStudents'=> $activeStudents,
            'inactiveStudents'=> $inactiveStudents,
            'newStudents'   => $newStudents,
            
            // Percentage Changes
            'totalChange'   => $totalChange,
            'activeChange'  => $activeChange,
            'inactiveChange'=> $inactiveChange,
            'newChange'     => $newChange,

            // Tables Data
            'borrowingRecords'=> $borrowingRecords,
            'returnedBooks' => $returnedBooks,

            // Additional Borrowing Stats
            'booksCurrentlyBorrowed' => BookReservation::where('status', 'picked_up')->whereNull('return_date')->count(),
            'overdueBooksCount' => BookReservation::where('status', 'picked_up')->whereNull('return_date')->where('due_date', '<', $now)->count(),
            'totalFinesCollected' => round(
                BookReservation::whereNotNull('fine_paid_at')
                    ->whereMonth('fine_paid_at', $now->month)
                    ->sum('fine_amount'),
                2
            ),
            'pendingFines' => round(
                BookReservation::where('status', 'picked_up')
                    ->whereNull('return_date')
                    ->where('due_date', '<', $now)
                    ->get()
                    ->sum(fn($r) => $r->calculateFine()),
                2
            ),
        ]);
    }
}