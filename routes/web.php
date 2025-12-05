<?php

/**
 * Web Routes
 * 
 * This file contains all the web routes for the Library Management System.
 * Routes are organized by authentication status and user roles.
 * 
 * @package App\Routes
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Student\BookController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Student\AnnouncementController as StudentAnnouncementController;
use App\Http\Controllers\Student\EbookController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Faculty\FacultyController;
use App\Http\Controllers\Assistant\AssistantController;
use App\Http\Controllers\Head\InventoryController;
use App\Http\Controllers\Head\AnnouncementController as HeadAnnouncementController;
use App\Http\Controllers\Head\ReservationController as HeadReservationController;
use App\Http\Controllers\Head\StudentRecordController;
use App\Http\Controllers\Head\ReportsController;
use App\Http\Controllers\Head\ProfileController as HeadProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Faculty\ProfileController as FacultyProfileController;
use App\Http\Controllers\Assistant\ProfileController as AssistantProfileController;
use App\Models\Book;
use App\Models\Ebook;



// Landing page
Route::get('/', function () {
    return view('index');
})->name('home');

//Authentication Routes
Route::prefix('auth')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    
    // Login
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Logout (requires authentication)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
});

//Admin Routes

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    
    // User Management
    Route::get('/users', [AdminController::class, 'showUsers'])->name('users');
    Route::post('/user/{user}/approve', [AdminController::class, 'approve'])->name('user.approve');
    Route::post('/user/{user}/reject', [AdminController::class, 'reject'])->name('user.reject');
    Route::get('/user/{user}/edit', [AdminController::class, 'editUser'])->name('user.edit');
    Route::put('/user/{user}', [AdminController::class, 'updateUser'])->name('user.update');
    
    // Announcements
    Route::get('/announcement', [AdminAnnouncementController::class, 'index'])->name('announcement');
    Route::post('/announcement', [AdminAnnouncementController::class, 'store'])->name('announcement.store');
    Route::put('/announcement/{announcement}', [AdminAnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('announcement.destroy');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');
    
    // Books & Ebooks
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    Route::get('/all-books', [AdminController::class, 'allBooks'])->name('books.all');
    Route::get('/all-ebooks', fn () => view('Admin.all_ebooks'))->name('ebooks.all');
});

// ============================================
// STUDENT ROUTES
// ============================================
// All routes require authentication and 'student' role
// Prefix: /student
// Route names: student.*

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // DASHBOARD: Main student dashboard with statistics and alerts
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'dashboard'])->name('dashboard');
    
    // BOOKS: Browse and reserve books
    Route::get('/books', [BookController::class, 'index'])->name('books'); // Popular books (6 most recent)
    Route::get('/books/all', [BookController::class, 'allBooks'])->name('books.all'); // All books by category
    Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show'); // Book details page
    Route::get('/books/{id}/check-reservation', [BookController::class, 'checkReservation'])->name('books.checkReservation'); // AJAX: Check if already reserved
    Route::post('/books/{id}/reserve', [BookController::class, 'reserve'])->name('books.reserve'); // Create reservation request
    
    // EBOOKS: View digital books
    Route::get('/all-ebooks', [EbookController::class, 'allEbooks'])->name('ebooks.all'); // All ebooks
    Route::get('/ebooks/{id}', [EbookController::class, 'show'])->name('ebooks.show'); // Ebook details and reader
    
    // PROFILE: Manage student profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show'); // View profile
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update profile
    
    // RESERVATIONS & BORROWED BOOKS: Track borrowing history
    Route::get('/borrowed-books', [BookController::class, 'borrowedBooks'])->name('borrowed'); // View borrowed books and requests
    Route::get('/request-books', function () {
        return redirect()->route('student.borrowed'); // Redirect to borrowed books
    })->name('requests');
    Route::delete('/requests/{id}/cancel', [BookController::class, 'cancelRequest'])->name('requests.cancel'); // Cancel pending/approved request
    
    // READING LIST: Save books for later (wishlist feature)
    Route::get('/reading-list', [\App\Http\Controllers\Student\ReadingListController::class, 'index'])->name('reading-list'); // View all saved books
    Route::post('/reading-list/{bookId}/add', [\App\Http\Controllers\Student\ReadingListController::class, 'add'])->name('reading-list.add'); // Add book to reading list
    Route::delete('/reading-list/{bookId}/remove', [\App\Http\Controllers\Student\ReadingListController::class, 'remove'])->name('reading-list.remove'); // Remove book from reading list
    
    // NOTIFICATIONS & ANNOUNCEMENTS: View system notifications
    Route::get('/announcements', function () {
        return redirect()->route('student.notifications'); // Redirect to notifications
    })->name('announcements');
    Route::get('/notifications', StudentNotificationController::class)->name('notifications'); // View all notifications
});

// Faculty Routes


Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [FacultyProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [FacultyProfileController::class, 'update'])->name('profile.update');
    
    // Books
    Route::get('/books', [FacultyController::class, 'books'])->name('books');
    Route::get('/books/{id}', [FacultyController::class, 'showBook'])->name('books.show');
    Route::post('/books/{id}/reserve', [FacultyController::class, 'reserveBook'])->name('books.reserve');
    
    // Ebooks
    Route::get('/ebooks/{id}', [FacultyController::class, 'showEbook'])->name('ebooks.show');
    
    // Reservations
    Route::get('/borrowed-books', [FacultyController::class, 'borrowedBooks'])->name('borrowedBooks');
    Route::get('/request-books', [FacultyController::class, 'requestBooks'])->name('requestBooks');
    
    // Announcements & Notifications
    Route::get('/announcement', [FacultyController::class, 'announcement'])->name('announcement');
    Route::get('/notification', [FacultyController::class, 'notification'])->name('notification');
});

// ============================================
// ASSISTANT ROUTES
// ============================================
// Library assistants can manage books, process reservations, and view students
// Prefix: /assistant
// Route names: assistant.*

Route::middleware(['auth', 'role:assistant'])->prefix('assistant')->name('assistant.')->group(function () {
    // DASHBOARD: Assistant dashboard with quick stats
    Route::get('/dashboard', [AssistantController::class, 'dashboard'])->name('dashboard');
    
    // PROFILE: Manage assistant profile
    Route::get('/profile', [AssistantProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [AssistantProfileController::class, 'update'])->name('profile.update');
    
    // BOOKS MANAGEMENT: Full CRUD operations for books
    Route::get('/all-books', [AssistantController::class, 'allBooks'])->name('allBooks'); // View all books
    Route::post('/all-books', [InventoryController::class, 'storeBook'])->name('books.store'); // Add new book
    Route::get('/all-books/{book}/edit', [InventoryController::class, 'editBook'])->name('books.edit'); // Edit book form
    Route::put('/all-books/{book}', [InventoryController::class, 'updateBook'])->name('books.update'); // Update book
    Route::delete('/all-books/{book}', [InventoryController::class, 'destroyBook'])->name('books.destroy'); // Delete book
    
    // EBOOKS MANAGEMENT: Full CRUD operations for ebooks
    Route::get('/all-ebooks', [AssistantController::class, 'allEbooks'])->name('allEbooks'); // View all ebooks
    Route::post('/all-ebooks', [InventoryController::class, 'storeEbook'])->name('ebooks.store'); // Add new ebook
    Route::get('/all-ebooks/{ebook}/edit', [InventoryController::class, 'editEbook'])->name('ebooks.edit'); // Edit ebook form
    Route::put('/all-ebooks/{ebook}', [InventoryController::class, 'updateEbook'])->name('ebooks.update'); // Update ebook
    Route::delete('/all-ebooks/{ebook}', [InventoryController::class, 'destroyEbook'])->name('ebooks.destroy'); // Delete ebook
    
    // RESERVATIONS MANAGEMENT: Process book reservations and returns
    Route::get('/borrow-return', function () {
        return redirect()->route('assistant.reservation'); // Redirect to reservation page
    })->name('borrowReturn');
    Route::get('/reservation', [AssistantController::class, 'reservation'])->name('reservation'); // View all reservations
    Route::put('/reservation/{id}/approve-request', [AssistantController::class, 'approveRequest'])->name('reservation.approveRequest'); // Approve pending request (status: pending → approved)
    Route::put('/reservation/{id}/approve', [AssistantController::class, 'approveReservation'])->name('reservation.approve'); // Mark as picked up (status: approved → picked_up, calculate due date)
    Route::put('/reservation/{id}/return', [AssistantController::class, 'returnBook'])->name('reservation.return'); // Return book (status: picked_up → returned, calculate fine if overdue)
    Route::put('/reservation/{id}/settle-fine', [AssistantController::class, 'settleFine'])->name('reservation.settleFine'); // Settle overdue fine (mark as paid)
    Route::delete('/reservation/{id}', [AssistantController::class, 'destroyReservation'])->name('reservation.destroy'); // Delete/cancel reservation
    
    // ADDITIONAL FEATURES: Other assistant functions
    Route::get('/manage-books', [AssistantController::class, 'manageBooks'])->name('manageBooks'); // Book management overview
    Route::get('/student', [AssistantController::class, 'student'])->name('student'); // View student records with fine tracking
    Route::get('/notification', [AssistantController::class, 'notification'])->name('notification'); // View notifications
    Route::get('/announcement', [AssistantController::class, 'announcement'])->name('announcement'); // View announcements
});

//Head Librarian Routes

Route::middleware(['auth', 'role:headlibrarian'])->prefix('head')->name('head.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Head\DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [HeadProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [HeadProfileController::class, 'update'])->name('profile.update');
    
    // Student Records
    Route::get('/student-record', [StudentRecordController::class, 'index'])->name('studentRecord');
    
    // Announcements
    Route::get('/announcement', [HeadAnnouncementController::class, 'index'])->name('announcement');
    Route::post('/announcement', [HeadAnnouncementController::class, 'store'])->name('announcement.store');
    Route::put('/announcement/{announcement}', [HeadAnnouncementController::class, 'update'])->name('announcement.update');
    Route::delete('/announcement/{announcement}', [HeadAnnouncementController::class, 'destroy'])->name('announcement.destroy');
    
    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::post('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    
    // Books Overview
    Route::get('/books', function () {
        $popularBooks = Book::latest()->take(6)->get();
        $popularEbooks = Ebook::latest()->take(6)->get();
        return view('head.books', [
            'popularBooks' => $popularBooks,
            'popularEbooks' => $popularEbooks,
        ]);
    })->name('books');
    
    // Reservations Management
    Route::get('/borrow-return', function () {
        return redirect()->route('head.reservation');
    })->name('borrowReturn');
    Route::get('/reservation', [HeadReservationController::class, 'index'])->name('reservation');
    Route::put('/reservation/{id}/approve-request', [HeadReservationController::class, 'approveRequest'])->name('reservation.approveRequest');
    Route::put('/reservation/{id}/approve', [HeadReservationController::class, 'approve'])->name('reservation.approve');
    Route::put('/reservation/{id}/return', [HeadReservationController::class, 'returnBook'])->name('reservation.return');
    Route::delete('/reservation/{id}', [HeadReservationController::class, 'destroy'])->name('reservation.destroy');
    
    // Inventory Management - Books
    Route::get('/all-books', [InventoryController::class, 'books'])->name('books.all');
    Route::post('/all-books', [InventoryController::class, 'storeBook'])->name('books.store');
    Route::get('/all-books/{book}/edit', [InventoryController::class, 'editBook'])->name('books.edit');
    Route::put('/all-books/{book}', [InventoryController::class, 'updateBook'])->name('books.update');
    Route::delete('/all-books/{book}', [InventoryController::class, 'destroyBook'])->name('books.destroy');
    
    // Inventory Management - Ebooks
    Route::get('/all-ebooks', [InventoryController::class, 'ebooks'])->name('ebooks.all');
    Route::post('/all-ebooks', [InventoryController::class, 'storeEbook'])->name('ebooks.store');
    Route::get('/all-ebooks/{ebook}/edit', [InventoryController::class, 'editEbook'])->name('ebooks.edit');
    Route::put('/all-ebooks/{ebook}', [InventoryController::class, 'updateEbook'])->name('ebooks.update');
    Route::delete('/all-ebooks/{ebook}', [InventoryController::class, 'destroyEbook'])->name('ebooks.destroy');
});
