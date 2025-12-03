<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
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

        return view('head.reservation', [
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

    public function approve(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as picked up.');
        }
        
        $reservation->update([
            'status' => 'picked_up',
            'pickup_date' => now(),
            'due_date' => now()->addDays(7), // 7 days loan period
        ]);

        return back()->with('success', 'Book marked as picked up successfully.');
    }

    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        $reservation->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        return back()->with('success', 'Book returned successfully.');
    }

    public function destroy($id)
    {
        $reservation = BookReservation::findOrFail($id);
        $reservation->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }
}

