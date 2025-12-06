<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BookReservation;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        // 1. Fetch System Announcements
        $announcements = Announcement::published()
            ->with('creator')
            ->visibleForRole($user->role)
            ->get()
            ->map(function ($announcement) {
                // Add a source field to identify this notification type
                $announcement->source = 'announcement';
                return $announcement;
            });

        // 2. Fetch Reservation-related Notifications (BookReservation model)
        $reservationNotifications = BookReservation::where('user_id', $user->userId)
            ->with('book')
            ->whereIn('status', ['approved', 'picked_up'])
            ->get()
            ->flatMap(function ($reservation) {
                $alerts = collect();
                
                // Alert A: Ready for Pickup
                if ($reservation->status === 'approved' && $reservation->reservation_date->isSameDay(Carbon::now())) {
                    $alerts->push((object) [
                        'source' => 'reservation',
                        'id' => 'res-pickup-' . $reservation->id,
                        'title' => 'Book Ready for Pickup!',
                        'body' => "Your reservation for '{$reservation->book->title}' has been approved and is ready for pickup at the library desk.",
                        'type' => 'reservation',
                        'publish_at' => $reservation->updated_at ?? Carbon::now(),
                        'creator' => null, // No specific user creator
                    ]);
                }
                
                // Alert B: Overdue Notice
                if ($reservation->isOverdue() && !$reservation->fine_paid_at) {
                    $fine = $reservation->calculateFine();
                    $alerts->push((object) [
                        'source' => 'reservation',
                        'id' => 'res-overdue-' . $reservation->id,
                        'title' => 'Overdue Book Alert!',
                        'body' => "The book '{$reservation->book->title}' was due on {$reservation->due_date->format('M d, Y')}. You currently owe ₱" . number_format($fine, 2) . " in fines.",
                        'type' => 'overdue',
                        'publish_at' => Carbon::now(), // Use current time for urgency
                        'creator' => null,
                    ]);
                }
                
                return $alerts;
            });
        
        // 3. Fetch New Book/Ebook Notifications (last 7 days)
        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        $newBooks = Book::where('created_at', '>=', $sevenDaysAgo)
            ->get()
            ->map(fn ($book) => (object) [
                'source' => 'new_book',
                'id' => 'book-' . $book->id,
                'title' => 'New Physical Book Added!',
                'body' => "A new book, '{$book->title}' by {$book->author}, has been added to the catalog. Check it out!",
                'type' => 'book_update',
                'publish_at' => $book->created_at,
                'creator' => null,
            ]);
            
        $newEbooks = Ebook::where('created_at', '>=', $sevenDaysAgo)
            ->get()
            ->map(fn ($ebook) => (object) [
                'source' => 'new_ebook',
                'id' => 'ebook-' . $ebook->id,
                'title' => 'New eBook Available!',
                'body' => "A new eBook, '{$ebook->title}' by {$ebook->author}, is now available in the digital library.",
                'type' => 'book_update',
                'publish_at' => $ebook->created_at,
                'creator' => null,
            ]);

        // 4. Combine and Sort Notifications
        $allNotifications = $announcements
            ->concat($reservationNotifications)
            ->concat($newBooks)
            ->concat($newEbooks)
            ->sortByDesc('publish_at');

        return view('student.notification', ['notifications' => $allNotifications]);
    }
}