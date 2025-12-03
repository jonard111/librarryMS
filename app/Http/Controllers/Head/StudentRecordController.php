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
        // Get all students
        $students = User::where('role', 'student')->get();
        
        // Calculate statistics
        $totalStudents = $students->count();
        $activeStudents = $students->where('account_status', 'approved')->count();
        $inactiveStudents = $students->whereIn('account_status', ['pending', 'rejected'])->count();
        
        // New students this month
        $newStudents = $students->filter(function ($student) {
            return Carbon::parse($student->registration_date)->isCurrentMonth();
        })->count();
        
        // Get student borrowing records with relationships
        $borrowingRecords = BookReservation::with(['user', 'book'])
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
                        return Carbon::parse($reservation->due_date)->isPast() && !$reservation->return_date;
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
        $lastMonthTotal = User::where('role', 'student')
            ->where('registration_date', '<', Carbon::now()->startOfMonth())
            ->count();
        $totalChange = $lastMonthTotal > 0 ? round((($totalStudents - $lastMonthTotal) / $lastMonthTotal) * 100) : 0;
        
        $lastMonthActive = User::where('role', 'student')
            ->where('account_status', 'approved')
            ->where('registration_date', '<', Carbon::now()->startOfMonth())
            ->count();
        $activeChange = $lastMonthActive > 0 ? round((($activeStudents - $lastMonthActive) / $lastMonthActive) * 100) : 0;
        
        $lastMonthInactive = User::where('role', 'student')
            ->whereIn('account_status', ['pending', 'rejected'])
            ->where('registration_date', '<', Carbon::now()->startOfMonth())
            ->count();
        $inactiveChange = $lastMonthInactive > 0 ? round((($inactiveStudents - $lastMonthInactive) / $lastMonthInactive) * 100) : 0;
        
        $lastMonthNew = User::where('role', 'student')
            ->whereBetween('registration_date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->count();
        $newChange = $lastMonthNew > 0 ? round((($newStudents - $lastMonthNew) / $lastMonthNew) * 100) : 0;
        
        return view('head.student_record', [
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

