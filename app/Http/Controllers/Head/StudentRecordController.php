<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentRecordController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // --- 1. Current Month/Total Student Counts ---
        $students = User::where('role', 'student')->get();
        $totalStudents = $students->count();
        $activeStudents = $students->where('account_status', 'approved')->count();
        $inactiveStudents = $students->whereIn('account_status', ['pending', 'rejected'])->count();
        
        // New students: Registered during the CURRENT calendar month
        $newStudents = User::where('role', 'student')
            ->whereBetween('registration_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();
        
        // --- 2. Last Month's Baseline Counts (for Comparison) ---
        
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
                return $current > 0 ? 100 : 0; 
            }
            return round((($current - $previous) / $previous) * 100, 1);
        };
        
        $totalChange    = $calculateChange($totalStudents, $totalLastMonth);
        $activeChange   = $calculateChange($activeStudents, $activeLastMonth);
        $inactiveChange = $calculateChange($inactiveStudents, $inactiveLastMonth);
        $newChange      = $calculateChange($newStudents, $newLastMonth);
        
        
        // --- 4. Overdue/Active Borrowing Records (for the Overdue table) ---
        // Fetches books currently out, are overdue, and calculates fines. (Similar to Assistant Logic)
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
                ];
            });

        // --- 5. Returned Books History (for the History table) ---
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


        // --- 6. Additional Statistics (for the secondary stat cards) ---
        $booksCurrentlyBorrowed = BookReservation::where('status', 'picked_up')->whereNull('return_date')->count();
        $overdueBooksCount = $borrowingRecords->count(); // Already calculated above
        
        $totalFinesCollected = round(
            BookReservation::whereNotNull('fine_paid_at')
                ->whereMonth('fine_paid_at', $now->month)
                ->sum('fine_amount'),
            2
        );
        $pendingFines = round(
            BookReservation::where('status', 'picked_up')
                ->whereNull('return_date')
                ->where('due_date', '<', $now)
                ->get()
                ->sum(fn($r) => $r->calculateFine()),
            2
        );
        
        return view('head.student_record', [
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'inactiveStudents' => $inactiveStudents,
            'newStudents' => $newStudents,
            'totalChange' => $totalChange,
            'activeChange' => $activeChange,
            'inactiveChange' => $inactiveChange,
            'newChange' => $newChange,
            'borrowingRecords' => $borrowingRecords, // Overdue/Active Loans
            'returnedBooks' => $returnedBooks, // History Table
            
            // Additional Stats
            'booksCurrentlyBorrowed' => $booksCurrentlyBorrowed,
            'overdueBooksCount' => $overdueBooksCount,
            'totalFinesCollected' => $totalFinesCollected,
            'pendingFines' => $pendingFines,
        ]);
    }
    
    // NOTE: This controller needs methods for approving/rejecting accounts if the Head is 
    // responsible for that, but those routes/methods are currently not present.
}