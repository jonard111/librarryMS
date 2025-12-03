<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\User;
use App\Models\BookReservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Total Books
        $totalBooks = Book::count();
        
        // Total Student Records
        $totalStudents = User::where('role', 'student')
            ->where('account_status', 'approved')
            ->count();
        
        // Reservations & Borrow/Return (active reservations)
        $activeReservations = BookReservation::whereIn('status', ['pending', 'approved', 'picked_up'])
            ->whereNull('return_date')
            ->count();
        
        // Total E-Books
        $totalEbooks = Ebook::count();
        
        // Analytics Data - Weekly (Last 7 days)
        $weekData = $this->getWeeklyBorrowData();
        
        // Analytics Data - Monthly (Last 4 weeks)
        $monthData = $this->getMonthlyBorrowData();
        
        // Analytics Data - Yearly (Last 12 months)
        $yearData = $this->getYearlyBorrowData();
        
        // Return Rate Analytics
        $returnRateData = $this->getReturnRateData();
        
        // Book Category Distribution
        $categoryData = $this->getCategoryDistribution();
        
        // User Activity (Student vs Faculty)
        $userActivityData = $this->getUserActivityData();
        
        // Popular Books
        $popularBooksData = $this->getPopularBooksData();
        
        return view('head.head_dashboard', compact(
            'totalBooks',
            'totalStudents',
            'activeReservations',
            'totalEbooks',
            'weekData',
            'monthData',
            'yearData',
            'returnRateData',
            'categoryData',
            'userActivityData',
            'popularBooksData'
        ));
    }
    
    /**
     * Get weekly borrow data (last 7 days)
     */
    private function getWeeklyBorrowData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->format('D');
            $labels[] = $dayName;
            
            $count = BookReservation::whereDate('reservation_date', $date->format('Y-m-d'))
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get monthly borrow data (last 4 weeks)
     */
    private function getMonthlyBorrowData()
    {
        $data = [];
        $labels = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $labels[] = 'Week ' . (4 - $i);
            
            $count = BookReservation::whereBetween('reservation_date', [
                $weekStart->format('Y-m-d'),
                $weekEnd->format('Y-m-d')
            ])->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get yearly borrow data (last 12 months)
     */
    private function getYearlyBorrowData()
    {
        $data = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $labels[] = $monthName;
            
            $count = BookReservation::whereYear('reservation_date', $month->year)
                ->whereMonth('reservation_date', $month->month)
                ->count();
            
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get return rate data (on-time vs overdue)
     */
    private function getReturnRateData()
    {
        $now = Carbon::now();
        
        // Get all returned reservations
        $returnedReservations = BookReservation::whereNotNull('return_date')
            ->whereNotNull('due_date')
            ->get();
        
        $onTime = 0;
        $overdue = 0;
        
        foreach ($returnedReservations as $reservation) {
            if ($reservation->return_date <= $reservation->due_date) {
                $onTime++;
            } else {
                $overdue++;
            }
        }
        
        // Get current overdue (not yet returned but past due)
        $currentOverdue = BookReservation::whereNotNull('due_date')
            ->whereNull('return_date')
            ->where('due_date', '<', $now)
            ->whereIn('status', ['picked_up', 'approved'])
            ->count();
        
        return [
            'onTime' => $onTime,
            'overdue' => $overdue,
            'currentOverdue' => $currentOverdue,
            'totalReturned' => $onTime + $overdue,
            'returnRate' => ($onTime + $overdue) > 0 ? round(($onTime / ($onTime + $overdue)) * 100, 1) : 0
        ];
    }
    
    /**
     * Get book category distribution
     */
    private function getCategoryDistribution()
    {
        $categories = Book::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();
        
        $labels = [];
        $data = [];
        $colors = [
            '#047857', '#059669', '#10b981', '#34d399', '#6ee7b7',
            '#a7f3d0', '#d1fae5', '#065f46', '#064e3b', '#022c22'
        ];
        
        foreach ($categories as $index => $category) {
            $labels[] = $category->category ?: 'Uncategorized';
            $data[] = $category->count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }
    
    /**
     * Get user activity data (Student vs Faculty)
     */
    private function getUserActivityData()
    {
        // Last 6 months
        $labels = [];
        $studentData = [];
        $facultyData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $labels[] = $monthName;
            
            // Student reservations
            $studentCount = BookReservation::whereHas('user', function($query) {
                $query->where('role', 'student');
            })
            ->whereYear('reservation_date', $month->year)
            ->whereMonth('reservation_date', $month->month)
            ->count();
            
            // Faculty reservations
            $facultyCount = BookReservation::whereHas('user', function($query) {
                $query->where('role', 'faculty');
            })
            ->whereYear('reservation_date', $month->year)
            ->whereMonth('reservation_date', $month->month)
            ->count();
            
            $studentData[] = $studentCount;
            $facultyData[] = $facultyCount;
        }
        
        return [
            'labels' => $labels,
            'student' => $studentData,
            'faculty' => $facultyData
        ];
    }
    
    /**
     * Get popular books data (top 10 most borrowed)
     */
    private function getPopularBooksData()
    {
        $popularBooks = BookReservation::select('book_id', DB::raw('count(*) as borrow_count'))
            ->with('book:id,title')
            ->groupBy('book_id')
            ->orderBy('borrow_count', 'desc')
            ->limit(10)
            ->get();
        
        $labels = [];
        $data = [];
        
        foreach ($popularBooks as $reservation) {
            if ($reservation->book) {
                $labels[] = strlen($reservation->book->title) > 30 
                    ? substr($reservation->book->title, 0, 30) . '...' 
                    : $reservation->book->title;
                $data[] = $reservation->borrow_count;
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}

