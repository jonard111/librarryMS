<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * BookController
 * * Handles all book-related operations for students including:
 * - Browsing books (popular and all books)
 * - Viewing book details
 * - Reserving books with loan duration selection
 * - Managing borrowed books and requests
 * - Checking for existing reservations
 */
class BookController extends Controller
{
    /**
     * Display popular books and ebooks on the student dashboard.
     * Fetches the 4 most recent physical books and 4 most recent ebooks.
     * Checks if the authenticated user has any active reservation for the displayed books.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- 1. Fetch data: Limit to 4 items for the dashboard view ---
        
        // Fetch the 4 most recently added physical books.
        $popularBooks = Book::latest()->take(4)->get();
        
        // Fetch the 4 most recently added digital books (ebooks).
        $popularEbooks = Ebook::latest()->take(4)->get();
        
        // --- 2. Check for existing active reservations by the user ---
        
        // Retrieve IDs of books the user currently has reserved (pending, approved, or picked up).
        $userReservations = BookReservation::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->pluck('book_id')
            ->toArray();
        
        // --- 3. Augment book data with reservation status flag ---
        
        // Add a boolean flag to each physical book indicating if the user has an active reservation.
        $popularBooks = $popularBooks->map(function($book) use ($userReservations) {
            $book->hasReservation = in_array($book->id, $userReservations);
            return $book;
        });
        
        // --- 4. Return the view with the prepared data ---

        return view('student.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    }

    /**
     * Display all books organized by category.
     * Includes reservation status check for each book.
     * @return \Illuminate\View\View
     */
    public function allBooks()
    {
        $categories = [
            'education' => 'Education & Learning',
            'science' => 'Science & Technology',
            'literature' => 'Literature / Fiction',
            'history' => 'History',
            'selfhelp' => 'Self-Help / Motivation',
        ];

        $books = Book::latest()->get();
        
        // Check which books the user already has reservations for
        $userReservations = BookReservation::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->pluck('book_id')
            ->toArray();
        
        // Add hasReservation flag to each book
        $books = $books->map(function($book) use ($userReservations) {
            $book->hasReservation = in_array($book->id, $userReservations);
            return $book;
        });

        $booksByCategory = $books->groupBy('category');

        return view('student.all_books', [
            'categories' => $categories,
            'booksByCategory' => $booksByCategory,
        ]);
    }

    /**
     * Check if user already has an active reservation for a book.
     * Used by AJAX calls to prevent duplicate reservations.
     * @param int $id Book ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkReservation($id)
    {
        $existingReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->first();
        
        if ($existingReservation) {
            $statusMessage = match($existingReservation->status) {
                'pending' => 'You already have a pending reservation request for this book. Please wait for approval.',
                'approved' => 'You already have an approved reservation for this book. Please pick it up from the library.',
                'picked_up' => 'You already have this book borrowed. Please return it before reserving again.',
                default => 'You already have an active reservation for this book.'
            };
            
            return response()->json([
                'hasReservation' => true,
                'message' => $statusMessage,
                'status' => $existingReservation->status
            ]);
        }
        
        return response()->json([
            'hasReservation' => false
        ]);
    }

    /**
     * Display detailed information about a specific book.
     * Includes reservation status and reading list status.
     * @param int $id Book ID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        $hasReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->exists();
        
        return view('student.book_details', compact('book', 'hasReservation'));
    }

    /**
     * Create a new book reservation request.
     * @param \Illuminate\Http\Request $request
     * @param int $id Book ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reserve(Request $request, $id)
    {
        // Log reservation attempt for debugging
        \Log::info('Reservation attempt', [
            'user_id' => Auth::id(),
            'book_id' => $id,
            'request_data' => $request->all()
        ]);
        
        $book = Book::findOrFail($id);
        
        // STEP 1: Check if user already has a pending/active reservation for this book
        $existingReservation = BookReservation::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->first();
        
        if ($existingReservation) {
            $statusMessage = match($existingReservation->status) {
                'pending' => 'You already have a pending reservation request for this book. Please wait for approval.',
                'approved' => 'You already have an approved reservation for this book. Please pick it up from the library.',
                'picked_up' => 'You already have this book borrowed. Please return it before reserving again.',
                default => 'You already have an active reservation for this book.'
            };
            
            return back()->with('error', $statusMessage)->withInput();
        }
        
        // STEP 2: Check if book has available copies
        $reservedCopies = BookReservation::where('book_id', $id)
            ->whereIn('status', ['pending', 'approved', 'picked_up'])
            ->count();
        
        if ($reservedCopies >= $book->copies) {
            return back()->with('error', 'No copies available for reservation at the moment.')->withInput();
        }
        
        // STEP 3: Validate loan duration input
        $validated = $request->validate([
            'loan_duration_value' => ['required', 'integer', 'min:1'],
            'loan_duration_unit' => ['required', Rule::in(['day', 'hour'])],
        ]);

        // STEP 4: Enforce maximum loan duration limits for students
        // Students: max 30 days or 72 hours
        $maxAllowed = $validated['loan_duration_unit'] === 'hour' ? 72 : 30;

        if ($validated['loan_duration_value'] > $maxAllowed) {
            return back()
                ->withErrors([
                    'loan_duration_value' => $validated['loan_duration_unit'] === 'hour'
                        ? 'Hourly loans are limited to 72 hours.'
                        : 'Daily loans are limited to 30 days.',
                ])
                ->withInput();
        }

        // STEP 5: Create the reservation record
        try {
            $reservation = BookReservation::create([
                'user_id' => Auth::id(),
                'book_id' => $id,
                'status' => 'pending',
                'reservation_date' => now(),
                'loan_duration' => $validated['loan_duration_value'],
                'loan_duration_unit' => $validated['loan_duration_unit'],
            ]);
            
            \Log::info('Reservation created successfully', [
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'book_id' => $reservation->book_id,
                'status' => $reservation->status
            ]);
            
            return redirect()->route('student.borrowed')->with('success', 'Book reservation requested successfully! You can view it in "My Book Requests" below.');
        } catch (\Exception $e) {
            \Log::error('Failed to create reservation', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'book_id' => $id
            ]);
            
            return back()->with('error', 'Failed to create reservation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display student's borrowed books and pending requests.
     * Shows two tables:
     * 1. Recent Borrowed - books that were picked up or returned (Active Loans & History).
     * 2. My Book Requests - pending, approved, rejected, or cancelled requests (Request Pipeline).
     * @return \Illuminate\View\View
     */
    public function borrowedBooks()
    {
        $userId = Auth::id();
        
        // 1. Get borrowed books (Active Loans & History: picked_up or returned status)
        $borrowedBooks = BookReservation::where('user_id', $userId)
            ->whereIn('status', ['picked_up', 'returned'])
            ->with('book')
            ->latest('pickup_date')
            ->get();
        
        // 2. Get book requests (Request Pipeline: pending, approved, rejected, cancelled)
        // FIX: EXCLUDE 'picked_up' to ensure active loans are only in $borrowedBooks.
        $bookRequests = BookReservation::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'rejected', 'cancelled'])
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->orderBy('reservation_date', 'desc')
            ->get();
        
        // Calculate statistics (these are often used for stat cards, assumed correct)
        $borrowedBooksCount = BookReservation::where('user_id', $userId)
            ->where('status', 'picked_up')
            ->count();
        
        $pendingRequests = BookReservation::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        
        // Calculate penalties (for returned books that were late)
        $penalties = BookReservation::where('user_id', $userId)
            ->where('status', 'returned')
            ->whereNotNull('due_date')
            ->whereColumn('return_date', '>', 'due_date')
            ->count();
        
        $approvedBooks = BookReservation::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();
        
        return view('student.borrowed_books', [
            'borrowedBooks' => $borrowedBooks,
            'bookRequests' => $bookRequests,
            'borrowedBooksCount' => $borrowedBooksCount,
            'pendingRequests' => $pendingRequests,
            'penalties' => $penalties,
            'approvedBooks' => $approvedBooks,
        ]);
    }

    /**
     * Cancel a pending or approved book reservation.
     * Only allows cancellation if status is 'pending' or 'approved' and belongs to the user.
     * @param int $id Reservation ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelRequest($id)
    {
        $reservation = BookReservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->whereIn('status', ['pending', 'approved'])
            ->firstOrFail();
        
        $reservation->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Book request cancelled successfully.');
    }
}