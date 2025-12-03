<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use App\Models\User;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Calculate statistics
        $booksBorrowed = BookReservation::where('status', 'picked_up')->count();
        $booksReturned = BookReservation::where('status', 'returned')->count();
        
        // Calculate penalties (overdue books)
        $penaltiesIssued = BookReservation::where('status', 'picked_up')
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::now())
            ->whereNull('return_date')
            ->count();
        
        // Active borrowers (users with picked_up books)
        $activeBorrowers = BookReservation::where('status', 'picked_up')
            ->distinct('user_id')
            ->count('user_id');
        
        // Get recent report data (recent reservations)
        $recentReports = BookReservation::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($reservation) {
                $reportType = $this->getReportType($reservation);
                $details = $this->getReportDetails($reservation);
                
                return [
                    'date' => $reservation->created_at->format('Y-m-d'),
                    'type' => $reportType,
                    'details' => $details,
                    'generated_by' => $reservation->user ? $reservation->user->first_name . ' ' . $reservation->user->last_name : 'System',
                ];
            });
        
        return view('head.reports', [
            'booksBorrowed' => $booksBorrowed,
            'booksReturned' => $booksReturned,
            'penaltiesIssued' => $penaltiesIssued,
            'activeBorrowers' => $activeBorrowers,
            'recentReports' => $recentReports,
        ]);
    }
    
    private function getReportType($reservation)
    {
        switch ($reservation->status) {
            case 'picked_up':
                return 'Borrow Transactions';
            case 'returned':
                return 'Return Reports';
            case 'approved':
                return 'Borrow Transactions';
            default:
                return 'User Activity';
        }
    }
    
    private function getReportDetails($reservation)
    {
        $bookTitle = $reservation->book ? $reservation->book->title : 'Unknown Book';
        $userName = $reservation->user ? $reservation->user->first_name . ' ' . $reservation->user->last_name : 'Unknown User';
        
        switch ($reservation->status) {
            case 'picked_up':
                return "Book '{$bookTitle}' borrowed by {$userName}";
            case 'returned':
                return "Book '{$bookTitle}' returned by {$userName}";
            case 'approved':
                return "Reservation approved for '{$bookTitle}' by {$userName}";
            default:
                return "Activity for '{$bookTitle}' by {$userName}";
        }
    }

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
            return [
                'id' => $reservation->id,
                'date' => $reservation->created_at->format('Y-m-d H:i:s'),
                'user_name' => $reservation->user ? $reservation->user->first_name . ' ' . $reservation->user->last_name : 'Unknown',
                'user_email' => $reservation->user ? $reservation->user->email : 'Unknown',
                'book_title' => $reservation->book ? $reservation->book->title : 'Unknown',
                'book_author' => $reservation->book ? $reservation->book->author : 'Unknown',
                'status' => $reservation->status,
                'reservation_date' => $reservation->reservation_date ? $reservation->reservation_date->format('Y-m-d') : null,
                'due_date' => $reservation->due_date ? $reservation->due_date->format('Y-m-d') : null,
                'return_date' => $reservation->return_date ? $reservation->return_date->format('Y-m-d') : null,
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

