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

    /**
     * Marks an approved request as picked up and calculates the due date based on loan duration.
     */
    public function approve(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        if ($reservation->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as picked up.');
        }

        $pickup = now();
        $duration = $reservation->loan_duration ?? 7; // Use stored loan duration (default to 7)
        $unit = $reservation->loan_duration_unit ?? 'day'; // Use stored unit (default to 'day')

        // Calculate Due Date dynamically
        $dueDate = $unit === 'hour'
            ? $pickup->copy()->addHours($duration)
            : $pickup->copy()->addDays($duration);
        
        $reservation->update([
            'status' => 'picked_up',
            'pickup_date' => $pickup,
            'due_date' => $dueDate, // Use dynamically calculated due date
        ]);

        return back()->with('success', 'Book marked as picked up successfully.');
    }

    public function returnBook(Request $request, $id)
    {
        $reservation = BookReservation::findOrFail($id);
        
        // This controller does not handle fines. If fines must be handled by the head, 
        // the logic from AssistantController@returnBook should be imported here.
        // Assuming the Head Librarian can bypass the fine check for simplicity/authority, 
        // or that fine logic is handled elsewhere for the Head.

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

    public function settleFine($id)
    {
        $reservation = BookReservation::with(['user', 'book'])->findOrFail($id);
        
        // Use the accessor/method defined on the BookReservation model to calculate fine
        $fineAmount = $reservation->calculateFine();

        if ($fineAmount <= 0) {
            return back()->with('error', 'No outstanding fine is currently due for this reservation.');
        }
        
        // Safety check to prevent double payment record
        if ($reservation->fine_paid_at) {
             return back()->with('error', 'The fine has already been settled.');
        }

        $reservation->update([
            'status'       => 'returned',
            'return_date'  => now(),
            'fine_amount'  => $fineAmount, // Record the calculated amount
            'fine_paid_at' => now(),       // Record the payment timestamp
        ]);

        return back()->with(
            'success',
            "{$reservation->user->full_name}'s fine of ₱" . number_format($fineAmount, 2) .
            " has been paid and '{$reservation->book->title}' has been marked as returned."
        );
    }
}
