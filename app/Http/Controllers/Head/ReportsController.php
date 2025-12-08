<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\User;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the main Reports dashboard with summary statistics and recent activity.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();
        $userRoleStudent = 'student'; 
        
        // --- 1. Summary Statistics (Card Data) ---
        $booksBorrowed = BookReservation::where('status', 'picked_up')->count();
        $booksReturned = BookReservation::where('status', 'returned')->count();
        
        // Penalties Issued (Count of currently overdue loans)
        $penaltiesIssued = BookReservation::where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now)
            ->whereNull('return_date')
            ->count();
        
        // Active borrowers (users with picked_up books)
        $activeBorrowers = BookReservation::where('status', 'picked_up')
            ->distinct('user_id')
            ->count('user_id');
            
        // --- 2. Head Librarian Specific Stats ---
        $totalBooks = Book::count();
        $totalEbooks = Ebook::count();
        $totalStudents = User::where('role', $userRoleStudent)->count();
        $activeReservations = BookReservation::whereIn('status', ['pending', 'approved'])->count();

        
        // --- 3. Recent Reports/Activity (Aggregated Transactions for Log) ---
        
        // Fetch aggregated transactions for the recent activity log
        $recentTransactions = BookReservation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(CASE WHEN status IN ("picked_up", "approved") THEN 1 END) as borrow_count'),
            DB::raw('COUNT(CASE WHEN status = "returned" THEN 1 END) as return_count'),
            DB::raw('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count'),
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->take(10)
        ->get();

        $recentReports = $recentTransactions->flatMap(function ($item) {
            $reports = collect();
            $dateString = Carbon::parse($item->date)->format('M d, Y');
            
            // Generate entries for the Blade loop
            if ($item->borrow_count > 0) {
                $reports->push(['date' => $dateString, 'type' => 'Borrow Transactions', 'details' => "Total of **{$item->borrow_count}** books were borrowed or approved for pickup.", 'generated_by' => 'System']);
            }
            if ($item->return_count > 0) {
                $reports->push(['date' => $dateString, 'type' => 'Return Reports', 'details' => "Total of **{$item->return_count}** books were successfully returned.", 'generated_by' => 'System']);
            }
            if ($item->pending_count > 0) {
                $reports->push(['date' => $dateString, 'type' => 'New Requests', 'details' => "Received **{$item->pending_count}** new reservation requests.", 'generated_by' => 'System']);
            }

            return $reports;
        });

        // Sort the final list by date again 
        $recentReports = $recentReports->sortByDesc('date')->values();
        
        // --- 4. RETURN VIEW (COMPACT ALL VARIABLES) ---
        
        return view('head.reports', [
            'booksBorrowed' => $booksBorrowed,
            'booksReturned' => $booksReturned,
            'penaltiesIssued' => $penaltiesIssued,
            'activeBorrowers' => $activeBorrowers,
            'recentReports' => $recentReports,
            'totalBooks' => $totalBooks,
            'totalEbooks' => $totalEbooks,
            'totalStudents' => $totalStudents,
            'activeReservations' => $activeReservations,
        ]);
    }

    
    
    /**
     * Handles the AJAX request to generate detailed report data.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:borrow,return,penalty,user',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

        $query = BookReservation::with(['user', 'book']);

        // Apply date filter if provided
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        // Filter by report type
        switch ($reportType) {
            case 'borrow':
                $query->whereIn('status', ['picked_up', 'approved']);
                break;
            case 'return':
                $query->where('status', 'returned');
                break;
            case 'penalty':
                $query->where('status', 'picked_up')
                    ->whereNotNull('due_date')
                    ->where('due_date', '<', Carbon::now())
                    ->whereNull('return_date');
                break;
            case 'user':
                // All reservations for user activity
                break;
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        // Format report data
        $reportData = $reports->map(function ($reservation) {
            // NOTE: $reservation->fine_amount must be defined as an accessor in BookReservation model
            $fineAmount = $reservation->fine_amount ?? '0.00'; 
            
            return [
                'id' => $reservation->id,
                'date' => $reservation->created_at->format('Y-m-d H:i:s'),
                'user_name' => optional($reservation->user)->first_name . ' ' . optional($reservation->user)->last_name,
                'user_email' => optional($reservation->user)->email,
                'book_title' => optional($reservation->book)->title,
                'book_author' => optional($reservation->book)->author,
                'status' => $reservation->status,
                'fine_amount' => $fineAmount, // Crash risk neutralized by accessor/null coalesce
                'reservation_date' => optional($reservation->reservation_date)->format('Y-m-d'),
                'due_date' => optional($reservation->due_date)->format('Y-m-d'),
                'return_date' => optional($reservation->return_date)->format('Y-m-d'),
                'is_overdue' => $reservation->status === 'picked_up' && $reservation->due_date && $reservation->due_date->isPast() && !$reservation->return_date,
            ];
        });

        $reportTypeLabel = [
            'borrow' => 'Borrow Transactions',
            'return' => 'Return Reports',
            'penalty' => 'Penalty Summary',
            'user' => 'User Activity',
        ][$reportType];

        return response()->json([
            'success' => true,
            'report_type' => $reportTypeLabel,
            'start_date' => $startDate ? $startDate->format('Y-m-d') : null,
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'total_records' => $reports->count(),
            'data' => $reportData,
        ]);
    }
}