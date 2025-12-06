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
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();
        
        // --- Summary Statistics ---
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
        
        // --- Recent Reports/Activity (Aggregated Transactions) ---
        
        $recentTransactions = BookReservation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(CASE WHEN status IN ("picked_up", "approved") THEN 1 END) as borrow_count'),
            DB::raw('COUNT(CASE WHEN status = "returned" THEN 1 END) as return_count'),
            DB::raw('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count'),
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->take(10) // Limit to the last 10 days of activity
        ->get();

        $recentReports = $recentTransactions->flatMap(function ($item) {
            $reports = collect();
            $dateString = Carbon::parse($item->date)->format('M d, Y');
            
            // Borrow Transactions (Picked Up or Approved)
            if ($item->borrow_count > 0) {
                $reports->push([
                    'date' => $dateString,
                    'type' => 'Borrow Transactions',
                    'details' => "Total of **{$item->borrow_count}** books were borrowed or approved for pickup.",
                ]);
            }
            
            // Return Reports
            if ($item->return_count > 0) {
                $reports->push([
                    'date' => $dateString,
                    'type' => 'Return Reports',
                    'details' => "Total of **{$item->return_count}** books were successfully returned.",
                ]);
            }
            
            // Pending Requests
             if ($item->pending_count > 0) {
                $reports->push([
                    'date' => $dateString,
                    'type' => 'New Requests',
                    'details' => "Received **{$item->pending_count}** new reservation requests.",
                ]);
            }

            return $reports;
        });

        // Sort the final list by date again (since flatMap might mix dates)
        $recentReports = $recentReports->sortByDesc('date')->values();
        
        // Remove helper methods as they are no longer used
        // private function getReportType()
        // private function getReportDetails()

        return view('head.reports', [
            'booksBorrowed' => $booksBorrowed,
            'booksReturned' => $booksReturned,
            'penaltiesIssued' => $penaltiesIssued,
            'activeBorrowers' => $activeBorrowers,
            'recentReports' => $recentReports,
        ]);
    }
    
    // ... (generate method remains unchanged)
    
    public function generate(Request $request)
    {
        // ... (generate method body remains unchanged)
    }
}