<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\BookReservation;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    private array $categoryOptions = [
        'education' => 'Education & Learning',
        'science' => 'Science & Technology',
        'literature' => 'Literature / Fiction',
        'history' => 'History',
        'selfhelp' => 'Self-Help / Motivation',
    ];
    public function dashboard()
{

    
    

    $totalUsers = User::where('role', '!=', 'admin')
                      ->where('account_status', 'approved')
                      ->count();

    $totalReports = BookReservation::count(); // Total reservations as reports

    // Active Announcements
    $activeAnnouncements = \App\Models\Announcement::where('status', 'published')
        ->where(function($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', Carbon::now());
        })
        ->count();
    
    $lastAnnouncement = \App\Models\Announcement::where('status', 'published')
        ->latest('publish_at')
        ->first();
    $lastAnnouncementDate = $lastAnnouncement ? $lastAnnouncement->publish_at->format('M d, Y') : 'No announcements';

    // Total E-Books
    $totalEbooks = Ebook::count();

    // Count approved users created this week
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();     

    $newUsersThisWeek = User::where('role', '!=', 'admin')
                            ->where('account_status', 'approved')
                            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                            ->count();

    // System Usage Data 

    // Weekly: users per day
    $usageWeek = User::selectRaw('DAYNAME(created_at) as day, COUNT(*) as total')
                     ->where('role', '!=', 'admin')
                     ->where('account_status', 'approved')
                     ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                     ->groupBy('day')
                     ->pluck('total', 'day');

    // Monthly: users per week 
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth   = Carbon::now()->endOfMonth();
    $usageMonthRaw = User::selectRaw('WEEK(created_at) as week, COUNT(*) as total')
                     ->where('role', '!=', 'admin')
                     ->where('account_status', 'approved')
                     ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                     ->groupBy('week')
                     ->pluck('total', 'week');

    $usageMonth = collect();
    foreach ($usageMonthRaw as $weekNum => $count) {
        $usageMonth["Week $weekNum"] = $count;
    }

    // Yearly: users per month 
    $startOfYear = Carbon::now()->startOfYear();
    $endOfYear   = Carbon::now()->endOfYear();
    $usageYearRaw = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                     ->where('role', '!=', 'admin')
                     ->where('account_status', 'approved')
                     ->whereBetween('created_at', [$startOfYear, $endOfYear])
                     ->groupBy('month')
                     ->pluck('total', 'month');

    $usageYear = collect();
    foreach ($usageYearRaw as $monthNum => $count) {
        $monthName = Carbon::create()->month($monthNum)->format('F'); 
        $usageYear[$monthName] = $count;
    }

    // Pending Approvals
    $pendingApprovals = User::where('role', '!=', 'admin')
                            ->where('account_status', 'pending')
                            ->count();

    // Total Books
    $totalBooks = Book::count();

    // Active Borrowers
    $activeBorrowers = BookReservation::whereIn('status', ['picked_up', 'approved'])
                                      ->whereNull('return_date')
                                      ->distinct('user_id')
                                      ->count('user_id');

    // Overdue Books
    $overdueBooks = BookReservation::where('status', 'picked_up')
                                   ->whereNotNull('due_date')
                                   ->where('due_date', '<', Carbon::now())
                                   ->whereNull('return_date')
                                   ->count();

    // User Role Distribution Data
    $roleDistribution = User::where('role', '!=', 'admin')
                            ->selectRaw('role, COUNT(*) as count')
                            ->groupBy('role')
                            ->pluck('count', 'role');

    // Account Status Overview Data
    $accountStatusData = User::where('role', '!=', 'admin')
                            ->selectRaw('account_status, COUNT(*) as count')
                            ->groupBy('account_status')
                            ->pluck('count', 'account_status');

    // Recent Activity Data
    $recentActivities = $this->getRecentActivities();

    return view('Admin.admin_dashboard', compact(
        'totalUsers',
        'newUsersThisWeek',
        'totalReports',
        'activeAnnouncements',
        'lastAnnouncementDate',
        'totalEbooks',
        'usageWeek',
        'usageMonth',
        'usageYear',
        'pendingApprovals',
        'totalBooks',
        'activeBorrowers',
        'overdueBooks',
        'roleDistribution',
        'accountStatusData',
        'recentActivities'
    ));
}

    /**
     * Get recent system activities
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent user registrations
        $recentUsers = User::where('role', '!=', 'admin')
                          ->orderBy('created_at', 'desc')
                          ->take(5)
                          ->get()
                          ->map(function($user) {
                              return [
                                  'type' => 'user_registration',
                                  'icon' => 'fa-user-plus',
                                  'color' => 'text-primary',
                                  'title' => 'New User Registration',
                                  'description' => $user->first_name . ' ' . $user->last_name . ' (' . $user->role . ') registered',
                                  'status' => $user->account_status,
                                  'date' => $user->created_at,
                              ];
                          });

        // Recent book reservations
        $recentReservations = BookReservation::with(['user', 'book'])
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get()
                                            ->map(function($reservation) {
                                                $bookTitle = $reservation->book ? $reservation->book->title : 'Unknown Book';
                                                $userName = $reservation->user ? $reservation->user->first_name . ' ' . $reservation->user->last_name : 'Unknown User';
                                                
                                                return [
                                                    'type' => 'book_reservation',
                                                    'icon' => 'fa-book',
                                                    'color' => 'text-success',
                                                    'title' => 'Book Reservation',
                                                    'description' => $userName . ' reserved "' . $bookTitle . '"',
                                                    'status' => $reservation->status,
                                                    'date' => $reservation->created_at,
                                                ];
                                            });

        // Recent announcements
        $recentAnnouncements = Announcement::orderBy('created_at', 'desc')
                                          ->take(5)
                                          ->get()
                                          ->map(function($announcement) {
                                              return [
                                                  'type' => 'announcement',
                                                  'icon' => 'fa-bullhorn',
                                                  'color' => 'text-warning',
                                                  'title' => 'New Announcement',
                                                  'description' => $announcement->title,
                                                  'status' => $announcement->status,
                                                  'date' => $announcement->created_at,
                                              ];
                                          });

        // Merge and sort by date
        $activities = $activities->merge($recentUsers)
                                 ->merge($recentReservations)
                                 ->merge($recentAnnouncements)
                                 ->sortByDesc('date')
                                 ->take(15)
                                 ->values();

        return $activities;
    }

    // MANAGE USERS PAGE
        public function showUsers()
    {
        $approvedUsers = User::where('account_status','approved')
                             ->where('role','!=','admin')
                             ->get();

        $pendingUsers = User::where('account_status','pending')
                            ->where('role','!=','admin')
                            ->get();
        return view('Admin.users', compact('approvedUsers','pendingUsers'));
    }

    public function approve(User $user)
    {
        $user->account_status = 'approved';
        $user->save();
        return back()->with('success','User approved successfully.');
    }

    public function reject(User $user)
    {
        $user->account_status = 'rejected';
        $user->save();
        return back()->with('success','User rejected.');
    }

    public function editUser(User $user)
    {
        return response()->json($user);
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->userId . ',userId'],
            'role' => ['required', 'in:student,faculty,headlibrarian,assistant,admin'],
            'account_status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $user->update($validated);

        return back()->with('success', 'User information updated successfully.');
    }

    public function books()
    {
        // Get most popular/recent books (limit to 6 for display)
        $popularBooks = Book::latest()->take(4)->get();
        
        // Get most popular/recent ebooks (limit to 6 for display)
        $popularEbooks = Ebook::latest()->take(4)->get();
        
        return view('Admin.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    }

    public function allBooks()
    {
        $categories = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];

        $booksByCategory = Book::latest()->get()->groupBy('category');

        return view('Admin.all_books', [
            'categories' => $categories,
            'booksByCategory' => $booksByCategory,
        ]);
    }

    public function reports()
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
        
        return view('Admin.reports', [
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

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:borrow,return,penalty,user',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $reportType = $request->report_type;
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;

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
                    ->where('due_date', '<', \Carbon\Carbon::now())
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
